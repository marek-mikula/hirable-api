<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Carbon\Carbon;
use Database\Factories\Factory;
use Domain\Position\Enums\EvaluationStateEnum;
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
            'creator_id' => $this->isMaking ? null : User::factory(),
            'position_candidate_id' => $this->isMaking ? null : PositionCandidate::factory(),
            'user_id' => $this->isMaking ? null : User::factory(),
            'state' => EvaluationStateEnum::WAITING,
            'stars' => null,
            'result' => null,
            'fill_until' => null,
            'reminded_at' => null,
        ];
    }

    public function ofFillUntil(?Carbon $fillUntil): static
    {
        return $this->state(fn (array $attributes) => [
            'fill_until' => $fillUntil,
        ]);
    }

    public function ofCreator(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'creator_id' => $user->id,
        ]);
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
