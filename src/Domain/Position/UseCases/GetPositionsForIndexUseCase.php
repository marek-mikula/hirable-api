<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetPositionsForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        $company = $user->loadMissing('company')->company;

        return Position::query()
            ->where('company_id', $company->id)
            ->when($gridQuery->hasSearchQuery(), function (PositionBuilder $query) use ($gridQuery): void {
                $query->where(function (PositionBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('name', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (PositionBuilder $query) use ($gridQuery): void {
                if ($gridQuery->hasSortKey('id')) {
                    $query->orderBy('id', $gridQuery->sort['id']->value);
                } elseif ($gridQuery->hasSortKey('state')) {
                    $query->orderBy('state', $gridQuery->sort['state']->value);
                } elseif ($gridQuery->hasSortKey('name')) {
                    $query->orderBy('name', $gridQuery->sort['name']->value);
                } elseif ($gridQuery->hasSortKey('lastname')) {
                    $query->orderBy('lastname', $gridQuery->sort['lastname']->value);
                } elseif ($gridQuery->hasSortKey('createdAt')) {
                    $query->orderBy('created_at', $gridQuery->sort['createdAt']->value);
                }
            }, function (PositionBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
