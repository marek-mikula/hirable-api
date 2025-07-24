<?php

declare(strict_types=1);

namespace Domain\Position\Database\Factories;

use Database\Factories\Factory;
use Domain\Application\Models\Application;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Enums\PositionCandidateStateEnum;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

/**
 * @extends BaseFactory<PositionCandidate>
 */
class PositionCandidateFactory extends Factory
{
    protected $model = PositionCandidate::class;

    public function definition(): array
    {
        return [
            'position_id' => $this->isMaking ? null : Position::factory(),
            'candidate_id' => $this->isMaking ? null : Candidate::factory(),
            'application_id' => $this->isMaking ? null : Application::factory(),
            'state' => 'new',
        ];
    }

    public function ofPosition(Position $position): static
    {
        return $this->state(fn (array $attributes) => [
            'position_id' => $position->id,
        ]);
    }

    public function ofCandidate(Candidate $candidate): static
    {
        return $this->state(fn (array $attributes) => [
            'candidate_id' => $candidate->id,
        ]);
    }

    public function ofApplication(Application $application): static
    {
        return $this->state(fn (array $attributes) => [
            'application_id' => $application->id,
        ]);
    }

    public function ofState(PositionCandidateStateEnum|string $state): static
    {
        return $this->state(fn (array $attributes) => [
            'state' => is_string($state) ? $state : $state->value,
        ]);
    }
}
