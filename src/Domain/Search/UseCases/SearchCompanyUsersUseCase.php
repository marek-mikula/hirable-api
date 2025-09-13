<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\UseCases\UseCase;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchCompanyUsersUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, SearchData $data, bool $ignoreAuth, Collection $roles): Collection
    {
        return User::query()
            ->select(['id', 'firstname', 'lastname'])
            ->whereCompany($user->company_id)
            ->when($roles->isNotEmpty(), function (Builder $query) use ($roles): void {
                $query->whereIn('company_role', $roles);
            })
            ->when($data->hasQuery(), function (Builder $query) use ($data): void {
                $query->where(function (Builder $query) use ($data): void {
                    $words = $data->getQueryWords();

                    foreach ($words as $word) {
                        if (is_numeric($word)) {
                            $query->orWhere('id', $word);
                        }

                        $query
                            ->orWhere('firstname', 'like', "%{$word}%")
                            ->orWhere('lastname', 'like', "%{$word}%")
                            ->orWhere('email', 'like', "%{$word}%");
                    }
                });
            })
            ->when($ignoreAuth, function (Builder $query) use ($user): void {
                $query->where('id', '<>', $user->id);
            })
            ->limit($data->limit)
            ->get()
            ->map(static fn (User $item): \Domain\Search\Data\ResultData => ResultData::from([
                'value' => $item->id,
                'label' => $item->label,
            ]));
    }
}
