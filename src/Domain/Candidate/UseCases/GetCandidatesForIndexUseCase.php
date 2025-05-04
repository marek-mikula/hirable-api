<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\Candidate\Models\Builders\CandidateBuilder;
use Domain\Candidate\Models\Candidate;
use Domain\User\Models\User;
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
                } elseif ($gridQuery->hasSortKey('firstname')) {
                    $query->orderBy('firstname', $gridQuery->sort['firstname']->value);
                } elseif ($gridQuery->hasSortKey('lastname')) {
                    $query->orderBy('lastname', $gridQuery->sort['lastname']->value);
                } elseif ($gridQuery->hasSortKey('email')) {
                    $query->orderBy('email', $gridQuery->sort['email']->value);
                } elseif ($gridQuery->hasSortKey('createdAt')) {
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
