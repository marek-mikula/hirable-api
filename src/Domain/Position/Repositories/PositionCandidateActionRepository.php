<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Enums\ActionTypeEnum;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Input\PositionCandidateActionStoreInput;
use Domain\Position\Repositories\Input\PositionCandidateActionUpdateInput;
use Illuminate\Support\Arr;

class PositionCandidateActionRepository implements PositionCandidateActionRepositoryInterface
{
    public function store(PositionCandidateActionStoreInput $input): PositionCandidateAction
    {
        $positionCandidateAction = new PositionCandidateAction();
        $positionCandidateAction->position_process_step_id = $input->positionProcessStep->id;
        $positionCandidateAction->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateAction->user_id = $input->user->id;
        $positionCandidateAction->type = $input->type;
        $positionCandidateAction->state = ActionStateEnum::ACTIVE;
        $positionCandidateAction->date = $input->date;
        $positionCandidateAction->time_start = $input->timeStart;
        $positionCandidateAction->time_end = $input->timeEnd;
        $positionCandidateAction->place = $input->place;
        $positionCandidateAction->instructions = $input->instructions;
        $positionCandidateAction->evaluation = $input->evaluation;
        $positionCandidateAction->name = $input->name;
        $positionCandidateAction->interview_form = $input->interviewForm;
        $positionCandidateAction->interview_type = $input->interviewType;
        $positionCandidateAction->interview_result = $input->interviewResult;
        $positionCandidateAction->assessment_center_result = $input->assessmentCenterResult;
        $positionCandidateAction->rejected_by_candidate = $input->rejectedByCandidate;
        $positionCandidateAction->rejection_reason = $input->rejectionReason;
        $positionCandidateAction->refusal_reason = $input->refusalReason;
        $positionCandidateAction->task_type = $input->taskType;
        $positionCandidateAction->task_result = $input->taskResult;
        $positionCandidateAction->offer_state = $input->offerState;
        $positionCandidateAction->offer_job_title = $input->offerJobTitle;
        $positionCandidateAction->offer_company = $input->offerCompany;
        $positionCandidateAction->offer_employment_forms = $input->offerEmploymentForms;
        $positionCandidateAction->offer_place = $input->offerPlace;
        $positionCandidateAction->offer_salary = $input->offerSalary;
        $positionCandidateAction->offer_salary_currency = $input->offerSalaryCurrency;
        $positionCandidateAction->offer_salary_frequency = $input->offerSalaryFrequency;
        $positionCandidateAction->offer_workload = $input->offerWorkload;
        $positionCandidateAction->offer_employment_relationship = $input->offerEmploymentRelationship;
        $positionCandidateAction->offer_start_date = $input->offerStartDate;
        $positionCandidateAction->offer_employment_duration = $input->offerEmploymentDuration;
        $positionCandidateAction->offer_certain_period_to = $input->offerCertainPeriodTo;
        $positionCandidateAction->offer_trial_period = $input->offerTrialPeriod;
        $positionCandidateAction->offer_candidate_note = $input->offerCandidateNote;
        $positionCandidateAction->real_start_date = $input->realStartDate;
        $positionCandidateAction->note = $input->note;

        throw_if(!$positionCandidateAction->save(), RepositoryException::stored(PositionCandidateAction::class));

        $positionCandidateAction->setRelation('positionProcessStep', $input->positionProcessStep);
        $positionCandidateAction->setRelation('positionCandidate', $input->positionCandidate);
        $positionCandidateAction->setRelation('user', $input->user);

        return $positionCandidateAction;
    }

    public function update(PositionCandidateAction $positionCandidateAction, PositionCandidateActionUpdateInput $input): PositionCandidateAction
    {
        $positionCandidateAction->date = $input->date;
        $positionCandidateAction->time_start = $input->timeStart;
        $positionCandidateAction->time_end = $input->timeEnd;
        $positionCandidateAction->place = $input->place;
        $positionCandidateAction->instructions = $input->instructions;
        $positionCandidateAction->evaluation = $input->evaluation;
        $positionCandidateAction->name = $input->name;
        $positionCandidateAction->interview_form = $input->interviewForm;
        $positionCandidateAction->interview_type = $input->interviewType;
        $positionCandidateAction->interview_result = $input->interviewResult;
        $positionCandidateAction->assessment_center_result = $input->assessmentCenterResult;
        $positionCandidateAction->rejected_by_candidate = $input->rejectedByCandidate;
        $positionCandidateAction->rejection_reason = $input->rejectionReason;
        $positionCandidateAction->refusal_reason = $input->refusalReason;
        $positionCandidateAction->task_type = $input->taskType;
        $positionCandidateAction->task_result = $input->taskResult;
        $positionCandidateAction->offer_state = $input->offerState;
        $positionCandidateAction->offer_job_title = $input->offerJobTitle;
        $positionCandidateAction->offer_company = $input->offerCompany;
        $positionCandidateAction->offer_employment_forms = $input->offerEmploymentForms;
        $positionCandidateAction->offer_place = $input->offerPlace;
        $positionCandidateAction->offer_salary = $input->offerSalary;
        $positionCandidateAction->offer_salary_currency = $input->offerSalaryCurrency;
        $positionCandidateAction->offer_salary_frequency = $input->offerSalaryFrequency;
        $positionCandidateAction->offer_workload = $input->offerWorkload;
        $positionCandidateAction->offer_employment_relationship = $input->offerEmploymentRelationship;
        $positionCandidateAction->offer_start_date = $input->offerStartDate;
        $positionCandidateAction->offer_employment_duration = $input->offerEmploymentDuration;
        $positionCandidateAction->offer_certain_period_to = $input->offerCertainPeriodTo;
        $positionCandidateAction->offer_trial_period = $input->offerTrialPeriod;
        $positionCandidateAction->offer_candidate_note = $input->offerCandidateNote;
        $positionCandidateAction->real_start_date = $input->realStartDate;
        $positionCandidateAction->note = $input->note;

        throw_if(!$positionCandidateAction->save(), RepositoryException::updated(PositionCandidateAction::class));

        return $positionCandidateAction;
    }

    public function setState(PositionCandidateAction $positionCandidateAction, ActionStateEnum $state): PositionCandidateAction
    {
        throw_if(
            condition: $positionCandidateAction->state === $state,
            exception: RepositoryException::updated(PositionCandidateAction::class)
        );

        throw_if(
            condition: !in_array($state, $positionCandidateAction->state->getNextStates()),
            exception: RepositoryException::updated(PositionCandidateAction::class)
        );

        $positionCandidateAction->state = $state;

        throw_if(!$positionCandidateAction->save(), RepositoryException::updated(PositionCandidateAction::class));

        return $positionCandidateAction;
    }

    public function existsByTypeAndState(PositionCandidate $positionCandidate, ActionTypeEnum $type, ActionStateEnum|array $state): bool
    {
        return PositionCandidateAction::query()
            ->where('position_candidate_id', $positionCandidate->id)
            ->where('type', $type)
            ->whereIn('state', Arr::wrap($state))
            ->exists();
    }
}
