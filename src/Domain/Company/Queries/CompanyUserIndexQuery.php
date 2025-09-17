<?php

declare(strict_types=1);

namespace Domain\Company\Queries;

use App\Queries\Query;
use Domain\Company\Models\Company;
use Domain\User\Models\Builders\UserBuilder;
use Domain\User\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Support\Grid\Data\Query\GridRequestQuery;

class CompanyUserIndexQuery extends Query
{
    public function handle(Company $company, GridRequestQuery $gridQuery): LengthAwarePaginator
    {
        return User::query()
            ->whereCompany($company->id)
            ->when($gridQuery->hasSearchQuery(), function (UserBuilder $query) use ($gridQuery): void {
                $query->where(function (UserBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('firstname', 'like', sprintf('%%%s%%', $gridQuery->searchQuery))
                        ->orWhere('lastname', 'like', sprintf('%%%s%%', $gridQuery->searchQuery))
                        ->orWhere('email', 'like', sprintf('%%%s%%', $gridQuery->searchQuery));
                });
            })
            ->when($gridQuery->hasSort(), function (UserBuilder $query) use ($gridQuery): void {
                // todo rewrite to common logic
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
                    } elseif ($column === 'role') {
                        $query->orderBy('company_role', $order->value);
                    } elseif ($column === 'firstname') {
                        $query->orderBy('firstname', $order->value);
                    } elseif ($column === 'lastname') {
                        $query->orderBy('lastname', $order->value);
                    } elseif ($column === 'email') {
                        $query->orderBy('email', $order->value);
                    } elseif ($column === 'createdAt') {
                        $query->orderBy('created_at', $order->value);
                    }
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
