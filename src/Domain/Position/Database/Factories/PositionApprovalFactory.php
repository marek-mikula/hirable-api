<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<PositionApproval>
 */
class PositionApprovalFactory extends Factory
{
    protected $model = PositionApproval::class;

    public function definition(): array
    {
        return [
            'model_has_position_id' => $this->isMaking ? null : ModelHasPosition::factory(),
            'position_id' => $this->isMaking ? null : Position::factory(),
            'state' => PositionApprovalStateEnum::PENDING,
            'node' => null,
            'decided_at' => null,
            'canceled_at' => null,
        ];
    }

    public function ofModelHasPosition(ModelHasPosition $modelHasPosition): static
    {
        return $this->state(fn (array $attributes) => [
            'model_has_position_id' => $modelHasPosition->id,
        ]);
    }

    public function ofState(PositionApprovalStateEnum $state): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => $state,
        ]);
    }
}
