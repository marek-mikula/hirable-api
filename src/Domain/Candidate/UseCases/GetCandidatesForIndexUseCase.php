<?php

namespace Domain\Candidate\UseCases;

use App\Models\Builders\CandidateBuilder;
use App\Models\Candidate;
use App\Models\User;
use App\UseCases\UseCase;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class GetCandidatesForIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        return Candidate::query()
            ->when($gridQuery->hasSearchQuery(), function (CandidateBuilder $query) use ($gridQuery): void {
                $query->where(function (CandidateBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('firstname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('email', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (CandidateBuilder $query) use ($gridQuery): void {
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

                if ($gridQuery->hasSortKey('createdAt')) {
                    $query->orderBy('created_at', $gridQuery->sort['createdAt']->value);
                }
            }, function (CandidateBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
