<?php

declare(strict_types=1);

namespace Domain\Position\Jobs;

use App\Jobs\ScheduleJob;
use Domain\Position\Models\PositionCandidateEvaluation;
use Domain\Position\UseCases\PositionCandidateEvaluationRemindUseCase;
use Illuminate\Database\Eloquent\Collection;

class RemindEvaluationUsersJob extends ScheduleJob
{
    public function handle(): void
    {
        PositionCandidateEvaluation::query()
            ->waiting()
            ->needsReminder()
            ->with('user')
            ->chunkById(100, function (Collection $evaluations): void {
                PositionCandidateEvaluationRemindUseCase::make()->handle($evaluations);
            }, 'position_candidate_evaluations.id', 'id');
    }
}
