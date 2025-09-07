<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateShare;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<PositionCandidateShare>
 */
class PositionCandidateShareFactory extends Factory
{
    protected $model = PositionCandidateShare::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->isMaking ? null : User::factory(),
            'position_candidate_id' => $this->isMaking ? null : PositionCandidate::factory(),
            'model_type' => User::class,
            'model_id' => $this->isMaking ? null : User::factory(),
        ];
    }

    public function ofPositionCandidate(PositionCandidate $positionCandidate): static
    {
        return $this->state(fn (array $attributes) => [
            'position_candidate_id' => $positionCandidate->id,
        ]);
    }

    public function ofModel(Model $model): static
    {
        return $this->state(fn (array $attributes) => [
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
        ]);
    }
}
