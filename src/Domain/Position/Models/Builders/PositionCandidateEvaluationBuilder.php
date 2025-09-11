<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\EvaluationStateEnum;
use Domain\Position\Services\PositionConfigService;
use Illuminate\Support\Facades\DB;

class PositionCandidateEvaluationBuilder extends Builder
{
    public function waiting(): static
    {
        return $this->where('state', EvaluationStateEnum::WAITING);
    }

    public function needsReminder(): static
    {
        $notifyDays = PositionConfigService::resolve()->getEvaluationNotifyDays();
        $notifyExpiredDays = PositionConfigService::resolve()->getEvaluationNotifyExpiredDays();

        return $this->where(function (PositionCandidateEvaluationBuilder $query) use (
            $notifyDays,
            $notifyExpiredDays,
        ): void {
            $query->where(function (PositionCandidateEvaluationBuilder $query) use ($notifyDays): void {
                $query
                    ->whereDate('fill_until', '>=', now())
                    ->where(DB::raw('ABS(DATEDIFF(COALESCE(reminded_at, created_at), NOW()))'), '>=', $notifyDays);
            })->orWhere(function (PositionCandidateEvaluationBuilder $query) use ($notifyExpiredDays): void {
                $query
                    ->whereDate('fill_until', '<', now())
                    ->where(DB::raw('ABS(DATEDIFF(COALESCE(reminded_at, created_at), NOW()))'), '>=', $notifyExpiredDays);
            });
        });
    }
}
