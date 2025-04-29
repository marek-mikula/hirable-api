<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\Models\User;
use App\UseCases\UseCase;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchCompanyUsersUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, SearchData $data, bool $ignoreAuth): Collection
    {
        $company = $user->loadMissing('company')->company;

        return User::query()
            ->select(['id', 'firstname', 'lastname'])
            ->when($data->hasQuery(), function (Builder $query) use ($data): void {
                $query->where(function (Builder $query) use ($data): void {
                    if (!empty($numericItems = $data->getNumericItems())) {
                        $query->whereIn('id', $numericItems);
                    }

                    $query
                        ->orWhereFullText(['firstname', 'lastname', 'email'], $data->getFulltextQuery(), ['mode' => 'boolean']);
                });
            })
            ->when($ignoreAuth, function (Builder $query) use ($user): void {
                $query->where('id', '<>', $user->id);
            })
            ->where('company_id', '=', $company->id)
            ->limit($data->limit)
            ->get()
            ->map(static fn (User $item) => ResultData::from([
                'value' => $item->id,
                'label' => $item->is($user) ? sprintf('%s (%s)', $item->full_name, __('common.you')) : $item->full_name,
            ]));
    }
}
