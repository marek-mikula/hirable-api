<?php

namespace Domain\Company\UseCases;

use App\Models\Builders\TokenBuilder;
use App\Models\Token;
use App\Models\User;
use App\UseCases\UseCase;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetInvitationsForIndexUseCase extends UseCase
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
                }

                if ($gridQuery->hasSortKey('validUntil')) {
                    $query->orderBy('valid_until', $gridQuery->sort['firstname']->value);
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
