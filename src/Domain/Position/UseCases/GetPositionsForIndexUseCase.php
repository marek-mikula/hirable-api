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
        return Position::query()
            ->with([
                'files',
                'hiringManagers',
                'approvers',
                'externalApprovers',
            ])
            ->where('company_id', $user->company_id)
            ->when($gridQuery->hasSearchQuery(), function (PositionBuilder $query) use ($gridQuery): void {
                $query->where(function (PositionBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('name', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('department', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (PositionBuilder $query) use ($gridQuery): void {
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
                    } elseif ($column === 'state') {
                        $query->orderBy('state', $order->value);
                    } elseif ($column === 'approvalState') {
                        $query->orderBy('approval_state', $order->value);
                    } elseif ($column === 'name') {
                        $query->orderBy('name', $order->value);
                    } elseif ($column === 'lastname') {
                        $query->orderBy('lastname', $order->value);
                    } elseif ($column === 'department') {
                        $query->orderBy('department', $order->value);
                    } elseif ($column === 'createdAt') {
                        $query->orderBy('created_at', $order->value);
                    }
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
