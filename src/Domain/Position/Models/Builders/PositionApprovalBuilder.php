<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\PositionApprovalStateEnum;
use Domain\Position\Services\PositionConfigService;

class PositionApprovalBuilder extends Builder
{
    public function needsReminder(): static
    {
        $remindDays = PositionConfigService::resolve()->getApprovalRemindDays();

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
