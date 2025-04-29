<?php

declare(strict_types=1);

namespace Domain\Company\UseCases;

use App\Models\Builders\TokenBuilder;
use App\Models\Token;
use App\Models\User;
use App\UseCases\UseCase;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetCompanyInvitationsForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        $company = $user->loadMissing('company')->company;

        return Token::query()
            ->whereCompany($company->id)
            ->when($gridQuery->hasSearchQuery(), function (TokenBuilder $query) use ($gridQuery): void {
                $query->where(function (TokenBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('data->email', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (TokenBuilder $query) use ($gridQuery): void {
                if ($gridQuery->hasSortKey('id')) {
                    $query->orderBy('id', $gridQuery->sort['id']->value);
                } elseif ($gridQuery->hasSortKey('email')) {
                    $query->orderBy('data->email', $gridQuery->sort['email']->value);
                } elseif ($gridQuery->hasSortKey('role')) {
                    $query->orderBy('data->role', $gridQuery->sort['role']->value);
                } elseif ($gridQuery->hasSortKey('usedAt')) {
                    $query->orderBy('used_at', $gridQuery->sort['usedAt']->value);
                } elseif ($gridQuery->hasSortKey('validUntil')) {
                    $query->orderBy('valid_until', $gridQuery->sort['validUntil']->value);
                } elseif ($gridQuery->hasSortKey('createdAt')) {
                    $query->orderBy('created_at', $gridQuery->sort['createdAt']->value);
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
