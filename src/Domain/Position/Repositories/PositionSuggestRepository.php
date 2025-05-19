<?php

declare(strict_types=1);

namespace Domain\Position\Repositories;

use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function suggestCertificates(User $user, ?string $value): array
    {
        $value = $value ? Str::lower($value) : $value;

        return Position::query()
            ->select('certificates')
            ->where('certificates', '<>', '[]')
            ->where('company_id', $user->company_id)
            ->when(!empty($value), function (PositionBuilder $query) use ($value): void {
                $query->where(DB::raw('LOWER(`certificates`)'), 'like', "%{$value}%");
            })
            ->pluck('certificates')
            ->flatten()
            ->when(!empty($value), function (Collection $items) use ($value): Collection {
                return $items->filter(fn (string $certificate) => Str::contains(Str::lower($certificate), $value));
            })
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public function suggestTechnologies(User $user, ?string $value): array
    {
        $value = $value ? Str::lower($value) : $value;

        return Position::query()
            ->select('technologies')
            ->where('technologies', '<>', '[]')
            ->where('company_id', $user->company_id)
            ->when(!empty($value), function (PositionBuilder $query) use ($value): void {
                $query->where(DB::raw('LOWER(`technologies`)'), 'like', "%{$value}%");
            })
            ->pluck('technologies')
            ->flatten()
            ->when(!empty($value), function (Collection $items) use ($value): Collection {
                return $items->filter(fn (string $technology) => Str::contains(Str::lower($technology), $value));
            })
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
