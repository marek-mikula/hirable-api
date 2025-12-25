<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Position;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchPositionsUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, SearchData $data, Collection $states): Collection
    {
        return Position::query()
            ->select(['id', 'name'])
            ->whereCompany($user->company_id)
            ->userCanSee($user)
            ->when($states->isNotEmpty(), function (Builder $query) use ($states) {
                $query->whereIn('state', $states);
            })
            ->when($data->hasQuery(), function (Builder $query) use ($data): void {
                $query->where(function (Builder $query) use ($data): void {
                    $words = $data->getQueryWords();

                    foreach ($words as $word) {
                        if (is_numeric($word)) {
                            $query->orWhere('id', $word);
                        }

                        $query
                            ->orWhere('name', 'like', sprintf('%%%s%%', $word));
                    }
                });
            })
            ->limit($data->limit)
            ->get()
            ->map(static fn (Position $item): ResultData => ResultData::from([
                'value' => $item->id,
                'label' => $item->name,
            ]));
    }
}
