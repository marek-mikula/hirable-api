<?php

namespace Domain\Company\UseCases;

use App\Models\Builders\UserBuilder;
use App\Models\User;
use App\UseCases\UseCase;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetCompanyUsersForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        $company = $user->loadMissing('company')->company;

        return User::query()
            ->whereCompany($company->id)
            ->when($gridQuery->hasSearchQuery(), function (UserBuilder $query) use ($gridQuery): void {
                $query->where(function (UserBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('firstname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('email', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (UserBuilder $query) use ($gridQuery): void {
                if ($gridQuery->hasSortKey('id')) {
                    $query->orderBy('id', $gridQuery->sort['id']->value);
                } elseif ($gridQuery->hasSortKey('role')) {
                    $query->orderBy('company_role', $gridQuery->sort['role']->value);
                } elseif ($gridQuery->hasSortKey('firstname')) {
                    $query->orderBy('firstname', $gridQuery->sort['firstname']->value);
                } elseif ($gridQuery->hasSortKey('lastname')) {
                    $query->orderBy('lastname', $gridQuery->sort['lastname']->value);
                } elseif ($gridQuery->hasSortKey('email')) {
                    $query->orderBy('email', $gridQuery->sort['email']->value);
                } elseif ($gridQuery->hasSortKey('createdAt')) {
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
