<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Repositories\Outputs\ModelHasPositionSyncOutput;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ModelHasPositionRepositoryInterface
{
    public function store(Position $position, Model $model, PositionRoleEnum $role): ModelHasPosition;

    /**
     * @param Collection<Model> $models
     * @return Collection<ModelHasPosition>
     */
    public function storeMany(Position $position, Collection $models, PositionRoleEnum $role): Collection;

    public function delete(Position $position, Model $model, PositionRoleEnum $role): void;

    /**
     * @param Position $position
     * @param Collection<Model> $existingModels
     * @param Collection<Model> $models
     * @param PositionRoleEnum $role
     */
    public function sync(Position $position, Collection $existingModels, Collection $models, PositionRoleEnum $role): ModelHasPositionSyncOutput;

    public function hasModelRoleOnPosition(Model $model, Position $position, PositionRoleEnum ...$role): bool;
}
