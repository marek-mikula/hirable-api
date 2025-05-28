<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

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
}
