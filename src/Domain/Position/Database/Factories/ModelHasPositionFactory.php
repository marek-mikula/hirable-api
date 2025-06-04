<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseFactory<ModelHasPosition>
 */
class ModelHasPositionFactory extends Factory
{
    protected $model = ModelHasPosition::class;

    public function definition(): array
    {
        return [
            'position_id' => $this->isMaking ? null : Position::factory(),
            'model_type' => User::class,
            'model_id' => $this->isMaking ? null : User::factory(),
            'role' => PositionRoleEnum::HIRING_MANAGER,
        ];
    }

    public function ofPosition(Position $position): static
    {
        return $this->state(fn (array $attributes) => [
            'position_id' => $position->id,
        ]);
    }

    public function ofModel(Model $model): static
    {
        return $this->state(fn (array $attributes) => [
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
        ]);
    }

    public function ofRole(PositionRoleEnum $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
        ]);
    }
}
