<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<PositionCandidateEvaluation>
 */
class PositionCandidateEvaluationFactory extends Factory
{
    protected $model = PositionCandidateEvaluation::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->isMaking ? null : User::factory(),
            'position_candidate_id' => $this->isMaking ? null : PositionCandidate::factory(),
            'model_type' => User::class,
            'model_id' => $this->isMaking ? null : User::factory(),
            'evaluation' => null,
            'result' => null,
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
