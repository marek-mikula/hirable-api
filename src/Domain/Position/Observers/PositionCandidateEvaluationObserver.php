<?php

declare(strict_types=1);

namespace Domain\Position\Observers;

use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Events\PositionCandidateEvaluationDeletedEvent;
use Domain\Position\Events\PositionCandidateEvaluationFilledEvent;
use Domain\Position\Events\PositionCandidateEvaluationRequestedEvent;
use Domain\Position\Models\PositionCandidateEvaluation;

class PositionCandidateEvaluationObserver
{
    public function created(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        if ($positionCandidateEvaluation->state === EvaluationStateEnum::WAITING) {
            PositionCandidateEvaluationRequestedEvent::dispatch($positionCandidateEvaluation);
        }
    }

    public function updated(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        if ($positionCandidateEvaluation->wasChanged('state') && $positionCandidateEvaluation->state === EvaluationStateEnum::FILLED) {
            PositionCandidateEvaluationFilledEvent::dispatch($positionCandidateEvaluation);
        }
    }

    public function deleting(PositionCandidateEvaluation $positionCandidateEvaluation): void
    {
        if ($positionCandidateEvaluation->state === EvaluationStateEnum::WAITING) {
            PositionCandidateEvaluationDeletedEvent::dispatch($positionCandidateEvaluation);
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
