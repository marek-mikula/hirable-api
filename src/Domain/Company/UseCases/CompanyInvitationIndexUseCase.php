<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\Company;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;
use Support\Token\Models\Builders\TokenBuilder;
use Support\Token\Models\Token;

class CompanyInvitationIndexUseCase extends UseCase
{
    public function handle(Company $company, GridRequestQuery $gridQuery): Paginator
    {
        return Token::query()
            ->whereCompany($company->id)
            ->when($gridQuery->hasSearchQuery(), function (TokenBuilder $query) use ($gridQuery): void {
                $query->where(function (TokenBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('data->email', 'like', sprintf('%%%s%%', $gridQuery->searchQuery));
                });
            })
            ->when($gridQuery->hasSort(), function (TokenBuilder $query) use ($gridQuery): void {
                // todo rewrite to common logic
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
                    } elseif ($column === 'email') {
                        $query->orderBy('data->email', $order->value);
                    } elseif ($column === 'role') {
                        $query->orderBy('data->role', $order->value);
                    } elseif ($column === 'usedAt') {
                        $query->orderBy('used_at', $order->value);
                    } elseif ($column === 'validUntil') {
                        $query->orderBy('valid_until', $order->value);
                    } elseif ($column === 'createdAt') {
                        $query->orderBy('created_at', $order->value);
                    }
                }
            }, function (TokenBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
