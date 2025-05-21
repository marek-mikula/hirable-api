<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;

class PositionSuggestRepository implements PositionSuggestRepositoryInterface
{
    public function suggestDepartments(User $user, ?string $value): array
    {
        return Position::query()
            ->select('department')
            ->whereNotNull('department')
            ->where('company_id', $user->company_id)
            ->when(!empty($value), function (PositionBuilder $query) use ($value): void {
                $query->where('department', 'like', "%{$value}%");
            })
            ->orderBy('department')
            ->distinct()
            ->pluck('department')
            ->all();
    }
}
