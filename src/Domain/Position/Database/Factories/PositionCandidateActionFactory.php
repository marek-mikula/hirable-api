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
            'position_process_step_id' => null,
            'position_candidate_id' => PositionCandidate::factory(),
            'user_id' => User::factory(),
            'type' => ActionTypeEnum::INTERVIEW,
            'state' => ActionStateEnum::ACTIVE,
            'date' => now(),
            'time_start' => now()->setTime(10, 0),
            'time_end' => now()->setTime(14, 0),
            'place' => fake()->address,
            'instructions' => null,
            'evaluation' => null,
            'name' => null,
            'interview_form' => 'personal',
            'interview_type' => 'technical',
            'interview_result' => null,
            'assessment_center_result' => null,
            'rejected_by_candidate' => null,
            'rejection_reason' => null,
            'refusal_reason' => null,
            'test_type' => null,
            'offer_state' => null,
            'offer_job_title' => null,
            'offer_company' => null,
            'offer_employment_forms' => null,
            'offer_place' => null,
            'offer_salary' => null,
            'offer_salary_currency' => null,
            'offer_salary_frequency' => null,
            'offer_workload' => null,
            'offer_employment_relationship' => null,
            'offer_start_date' => null,
            'offer_employment_duration' => null,
            'offer_certain_period_to' => null,
            'offer_trial_period' => null,
            'offer_candidate_note' => null,
            'real_start_date' => null,
            'note' => fake()->text(500),
        ];
    }

    public function ofUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
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
