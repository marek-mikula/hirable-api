<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\PositionApprovalStateEnum;

class PositionApprovalBuilder extends Builder
{
    public function needsReminder(): static
    {
        $remindDays = (int) config('position.approval.remind_days');

        return $this->where(function (PositionApprovalBuilder $query) use ($remindDays): void {
            $query
                ->where(function (PositionApprovalBuilder $query) use ($remindDays): void {
                    $query
                        ->where(function (PositionApprovalBuilder $query) use ($remindDays): void {
                            $query
                                ->whereNull('reminded_at')
                                ->whereDate('created_at', '<=', now()->subDays($remindDays));
                        })
                        ->orWhereDate('reminded_at', '<=', now()->subDays($remindDays));
                })
                ->where('state', PositionApprovalStateEnum::PENDING->value);
        });
    }
}
