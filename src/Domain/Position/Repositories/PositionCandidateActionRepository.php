<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use App\Exceptions\RepositoryException;
use Domain\Position\Enums\ActionStateEnum;
use Domain\Position\Models\PositionCandidateAction;
use Domain\Position\Repositories\Inputs\PositionCandidateActionStoreInput;

class PositionCandidateActionRepository implements PositionCandidateActionRepositoryInterface
{
    public function store(PositionCandidateActionStoreInput $input): PositionCandidateAction
    {
        $positionCandidateAction = new PositionCandidateAction();
        $positionCandidateAction->position_candidate_id = $input->positionCandidate->id;
        $positionCandidateAction->type = $input->type;
        $positionCandidateAction->state = ActionStateEnum::CREATED;
        $positionCandidateAction->date = $input->date;
        $positionCandidateAction->time_start = $input->timeStart;
        $positionCandidateAction->time_end = $input->timeEnd;
        $positionCandidateAction->interview_form = $input->interviewForm;
        $positionCandidateAction->interview_type = $input->interviewType;
        $positionCandidateAction->place = $input->place;
        $positionCandidateAction->test_type = $input->testType;
        $positionCandidateAction->instructions = $input->instructions;
        $positionCandidateAction->result = $input->result;
        $positionCandidateAction->rejection_reason = $input->rejectionReason;
        $positionCandidateAction->refusal_reason = $input->refusalReason;
        $positionCandidateAction->name = $input->name;
        $positionCandidateAction->note = $input->note;

        throw_if(!$positionCandidateAction->save(), RepositoryException::stored(PositionCandidateAction::class));

        $positionCandidateAction->setRelation('positionCandidate', $input->positionCandidate);

        return $positionCandidateAction;
    }

    public function setState(PositionCandidateAction $positionCandidateAction, ActionStateEnum $state): PositionCandidateAction
    {
        throw_if(
            condition: $positionCandidateAction->state === $state,
            exception: RepositoryException::updated(PositionCandidateAction::class)
        );

        throw_if(
            condition: !in_array($state, $positionCandidateAction->type->getAllowedStates()),
            exception: RepositoryException::updated(PositionCandidateAction::class)
        );

        throw_if(
            condition: !in_array($state, $positionCandidateAction->state->getNextStatesByType($positionCandidateAction->type)),
            exception: RepositoryException::updated(PositionCandidateAction::class)
        );

        $positionCandidateAction->state = $state;

        throw_if(!$positionCandidateAction->save(), RepositoryException::updated(PositionCandidateAction::class));

        return $positionCandidateAction;
    }
}
