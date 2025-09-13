<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Carbon\Carbon;
use Database\Factories\Factory;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Models\ModelHasPosition;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionApproval;
use Support\Token\Models\Token;

/**
 * @extends Factory<PositionApproval>
 */
class PositionApprovalFactory extends Factory
{
    protected $model = PositionApproval::class;

    public function definition(): array
    {
        return [
            'model_has_position_id' => $this->isMaking ? null : ModelHasPosition::factory(),
            'position_id' => $this->isMaking ? null : Position::factory(),
            'round' => 1,
            'token_id' => null,
            'state' => PositionApprovalStateEnum::PENDING,
            'note' => null,
            'decided_at' => null,
            'reminded_at' => null,
        ];
    }

    public function ofRemindedAt(?Carbon $timestamp): static
    {
        return $this->state(fn (array $attributes): array => [
            'reminded_at' => $timestamp,
        ]);
    }

    public function ofToken(Token $token): static
    {
        return $this->state(fn (array $attributes): array => [
            'token_id' => $token->id,
        ]);
    }

    public function ofModelHasPosition(ModelHasPosition $modelHasPosition): static
    {
        return $this->state(fn (array $attributes): array => [
            'model_has_position_id' => $modelHasPosition->id,
            'position_id' => $modelHasPosition->position_id,
        ]);
    }

    public function ofState(PositionApprovalStateEnum $state): static
    {
        return $this->state(fn (array $attributes): array => [
            'state' => $state,
        ]);
    }

    public function approved(?string $note = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'state' => PositionApprovalStateEnum::APPROVED,
            'note' => $note,
        ]);
    }

    public function rejected(?string $note = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'state' => PositionApprovalStateEnum::APPROVED,
            'note' => $note,
        ]);
    }

    public function canceled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'state' => PositionApprovalStateEnum::APPROVED,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes): array => [
            'state' => PositionApprovalStateEnum::APPROVED,
        ]);
    }
}
