<?php

namespace Domain\User\UseCases;

use App\Models\Builders\UserBuilder;
use App\Models\User;
use App\UseCases\UseCase;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetUsersForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        return User::query()
            ->where('company_id', '=', $user->company_id)
            ->when($gridQuery->hasSearchQuery(), function (UserBuilder $query) use ($gridQuery): void {
                $query->where(function (UserBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('firstname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (UserBuilder $query) use ($gridQuery): void {
                if ($gridQuery->hasSortKey('id')) {
                    $query->orderBy('id', $gridQuery->sort['id']->value);
                }

                if ($gridQuery->hasSortKey('firstname')) {
                    $query->orderBy('firstname', $gridQuery->sort['firstname']->value);
                }

                if ($gridQuery->hasSortKey('lastname')) {
                    $query->orderBy('lastname', $gridQuery->sort['lastname']->value);
                }

                if ($gridQuery->hasSortKey('email')) {
                    $query->orderBy('email', $gridQuery->sort['email']->value);
                }

                if ($gridQuery->hasSortKey('phone')) {
                    $query->orderBy('phone', $gridQuery->sort['phone']->value);
                }

                if ($gridQuery->hasSortKey('role')) {
                    $query->orderBy('role', $gridQuery->sort['role']->value);
                }

                if ($gridQuery->hasSortKey('createdAt')) {
                    $query->orderBy('created_at', $gridQuery->sort['createdAt']->value);
                }
            }, function (UserBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
