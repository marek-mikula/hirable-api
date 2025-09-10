<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Notifications\PositionCandidateEvaluationCanceledNotification;
use Domain\Position\Notifications\PositionCandidateEvaluationRequestedNotification;

class PositionCandidateEvaluationObserver
{
    public function created(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        if ($positionCandidateEvaluation->state === EvaluationStateEnum::WAITING) {
            $positionCandidateEvaluation->user->notify(new PositionCandidateEvaluationRequestedNotification($positionCandidateEvaluation));
        }
    }

    public function updated(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        //
    }

    public function deleting(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        if ($positionCandidateEvaluation->state === EvaluationStateEnum::WAITING) {
            $positionCandidateEvaluation->user->notify(new PositionCandidateEvaluationCanceledNotification(
                creator: $positionCandidateEvaluation->creator,
                positionCandidate: $positionCandidateEvaluation->positionCandidate,
            ));
        }
    }

    public function deleted(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        //
    }

    public function restored(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        //
    }

    public function forceDeleted(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        //
    }
}
