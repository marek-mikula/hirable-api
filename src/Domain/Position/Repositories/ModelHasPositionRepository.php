<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Outputs\ModelHasPositionSyncOutput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class ModelHasPositionRepository implements ModelHasPositionRepositoryInterface
{
    public function store(Position $position, Model $model, PositionRoleEnum $role): ModelHasPosition
    {
        $modelHasPosition = new ModelHasPosition();

        $modelHasPosition->position_id = $position->id;
        $modelHasPosition->model_type = $model::class;
        $modelHasPosition->model_id = (int) $model->getKey();
        $modelHasPosition->role = $role;

        throw_if(!$modelHasPosition->save(), RepositoryException::stored(ModelHasPosition::class));

        $modelHasPosition->setRelation('position', $position);

        return $modelHasPosition;
    }

    public function storeMany(Position $position, Collection $models, PositionRoleEnum $role): Collection
    {
        return $models->map(fn (Model $model) => $this->store($position, $model, $role));
    }

    public function delete(Position $position, Model $model, PositionRoleEnum $role): void
    {
        $state = ModelHasPosition::query()
            ->where('model_type', $model::class)
            ->where('model_id', $model->getKey())
            ->where('role', $role->value)
            ->delete();

        throw_if(!$state, RepositoryException::deleted(ModelHasPosition::class));
    }

    public function sync(
        Position $position,
        Collection $existingModels,
        Collection $models,
        PositionRoleEnum $role
    ): ModelHasPositionSyncOutput {
        $existingModels = $existingModels->keyBy(fn (Model $model) => $model->getKey());

        $stored = collect();
        $deleted = collect();

        /** @var Model $model */
        foreach ($models as $model) {
            // model already exists
            if ($existingModels->has($model->getKey())) {
                $existingModels->forget($model->getKey());

                continue;
            }

            $this->store($position, $model, $role);

            $stored->push($model);
        }

        /** @var Model $model */
        foreach ($existingModels as $model) {
            $this->delete($position, $model, $role);

            $deleted->push($model);
        }

        return new ModelHasPositionSyncOutput(stored: $stored, deleted: $deleted);
    }

    public function hasModelRoleOnPosition(Model $model, Position $position, PositionRoleEnum ...$role): bool
    {
        return ModelHasPosition::query()
            ->where('model_type', $model::class)
            ->where('model_id', $model->getKey())
            ->whereIn('role', Arr::pluck($role, 'value'))
            ->exists();
    }
}
