<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\UseCases\UseCase;
use Domain\Company\Models\CompanyContact;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchCompanyContactsUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, SearchData $data): Collection
    {
        $company = $user->loadMissing('company')->company;

        return CompanyContact::query()
            ->select(['id', 'firstname', 'lastname', 'company_name'])
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
                            ->orWhere('email', 'like', "%{$word}%")
                            ->orWhere('company_name', 'like', "%{$word}%");
                    }
                });
            })
            ->where('company_id', '=', $company->id)
            ->limit($data->limit)
            ->get()
            ->map(static fn (CompanyContact $item) => ResultData::from([
                'value' => $item->id,
                'label' => $item->company_name ? sprintf('%s (%s)', $item->full_name, $item->company_name) : $item->full_name,
            ]));
    }
}
