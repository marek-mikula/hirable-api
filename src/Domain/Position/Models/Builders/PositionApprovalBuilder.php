<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\PositionApprovalStateEnum;

class PositionApprovalBuilder extends Builder
{
    public function needsNotification(): static
    {
        return $this->where(function (PositionApprovalBuilder $query): void {
            $query
                ->where(function (PositionApprovalBuilder $query): void {
                    $query
                        ->where(function (PositionApprovalBuilder $query): void {
                            $query
                                ->whereNull('notified_at')
                                ->whereDate('created_at', '<=', now()->subDays(2));
                        })
                        ->orWhereDate('notified_at', '<=', now()->subDays(2));
                })
                ->where('state', PositionApprovalStateEnum::PENDING->value);
        });
    }
}
