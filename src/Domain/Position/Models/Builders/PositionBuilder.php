<?php

declare(strict_types=1);

namespace Domain\Position\Models\Builders;

use App\Models\Builders\Builder;
use Domain\Position\Enums\PositionStateEnum;

class PositionBuilder extends Builder
{
    public function whereCompany(int $id): static
    {
        return $this->where('company_id', $id);
    }

    public function approvalExpired(): static
    {
        return $this->where(function (PositionBuilder $query): void {
            $query
                ->whereDate('approve_until', '<', now())
                ->where('state', PositionStateEnum::APPROVAL_PENDING->value);
        });
    }
}
