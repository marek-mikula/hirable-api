<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<PositionCandidateAction>
 */
class PositionCandidateActionFactory extends Factory
{
    protected $model = PositionCandidateAction::class;

    public function definition(): array
    {
        return [
            'position_candidate_id' => PositionCandidate::factory(),
            'user_id' => User::factory(),
            'type' => ActionTypeEnum::INTERVIEW,
            'state' => ActionStateEnum::CREATED,
            'date' => now(),
            'time_start' => now()->setTime(10, 0),
            'time_end' => now()->setTime(14, 0),
            'note' => fake()->text(500),
            'place' => fake()->address,
            'instructions' => null,
            'result' => null,
            'name' => null,
            'interview_form' => 'personal',
            'interview_type' => 'technical',
            'rejection_reason' => null,
            'refusal_reason' => null,
            'test_type' => null,
        ];
    }

    public function ofPositionCandidate(PositionCandidate $positionCandidate): static
    {
        return $this->state(fn (array $attributes) => [
            'position_candidate_id' => $positionCandidate->id,
        ]);
    }

    public function ofType(ActionTypeEnum $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    public function ofState(ActionStateEnum $state): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => $state,
        ]);
    }
}
