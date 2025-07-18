<?php

declare(strict_types=1);

namespace Domain\Candidate\UseCases;

use App\UseCases\UseCase;
use Domain\Candidate\Models\Builders\CandidateBuilder;
use Domain\Candidate\Models\Candidate;
use Domain\User\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Support\Grid\Data\Query\GridRequestQuery;

class CandidateIndexUseCase extends UseCase
{
    public function handle(User $user, GridRequestQuery $gridQuery): Paginator
    {
        return Candidate::query()
            ->whereCompany($user->company_id)
            ->when($gridQuery->hasSearchQuery(), function (CandidateBuilder $query) use ($gridQuery): void {
                $query->where(function (CandidateBuilder $query) use ($gridQuery): void {
                    $query
                        ->where('firstname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('lastname', 'like', "%{$gridQuery->searchQuery}%")
                        ->orWhere('email', 'like', "%{$gridQuery->searchQuery}%");
                });
            })
            ->when($gridQuery->hasSort(), function (CandidateBuilder $query) use ($gridQuery): void {
                // todo rewrite to common logic
                foreach ($gridQuery->sort as $column => $order) {
                    if ($column === 'id') {
                        $query->orderBy('id', $order->value);
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
            }, function (CandidateBuilder $query): void {
                $query->orderBy('id', 'desc');
            })
            ->paginate(
                perPage: $gridQuery->perPage->value,
                page: $gridQuery->page,
            );
    }
}
