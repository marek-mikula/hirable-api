<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\Notifications\PositionCandidateEvaluationReminderNotification;
use Domain\Position\Repositories\PositionCandidateEvaluationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PositionCandidateEvaluationRemindUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateEvaluationRepositoryInterface $positionCandidateEvaluationRepository,
    ) {
    }

    /**
     * @param Collection<PositionCandidateEvaluation> $evaluations
     * @return void
     */
    public function handle(Collection $evaluations): void
    {
        /** @var PositionCandidateEvaluation $evaluation */
        foreach ($evaluations as $evaluation) {
            DB::transaction(function () use ($evaluation): void {
                $notification = new PositionCandidateEvaluationReminderNotification($evaluation);

                $evaluation->user->notify($notification);

                $this->positionCandidateEvaluationRepository->setRemindedAt($evaluation);
            }, attempts: 5);
        }
    }
}
