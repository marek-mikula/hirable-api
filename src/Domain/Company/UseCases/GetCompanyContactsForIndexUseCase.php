<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\Builders\CompanyContactBuilder;
use Domain\Company\Models\CompanyContact;
use Domain\User\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetCompanyContactsForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        $company = $user->loadMissing('company')->company;

        return CompanyContact::query()
            ->whereCompany($company->id)
            ->when($gridQuery->hasSearchQuery(), function (CompanyContactBuilder $query) use ($gridQuery): void {
                $query->where(function (CompanyContactBuilder $query) use ($gridQuery): void {
                    $query
                        ->orWhere('firstname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('email', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (CompanyContactBuilder $query) use ($gridQuery): void {
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
                    } elseif ($column === 'firstname') {
                        $query->orderBy('firstname', $order->value);
                    } elseif ($column === 'lastname') {
                        $query->orderBy('lastname', $order->value);
                    } elseif ($column === 'email') {
                        $query->orderBy('email', $order->value);
                    } elseif ($column === 'companyName') {
                        $query->orderBy('company_name', $order->value);
                    }
                }
            }, function (CompanyContactBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
