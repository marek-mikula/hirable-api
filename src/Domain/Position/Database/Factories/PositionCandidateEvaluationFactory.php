<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;

/**
 * @extends Factory<PositionCandidateEvaluation>
 */
class PositionCandidateEvaluationFactory extends Factory
{
    protected $model = PositionCandidateEvaluation::class;

    public function definition(): array
    {
        return [
            'position_candidate_id' => $this->isMaking ? null : PositionCandidate::factory(),
            'user_id' => $this->isMaking ? null : User::factory(),
            'evaluation' => null,
            'result' => null,
        ];
    }

    public function ofPositionCandidate(PositionCandidate $positionCandidate): static
    {
        return $this->state(fn (array $attributes) => [
            'position_candidate_id' => $positionCandidate->id,
        ]);
    }

    public function ofUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
