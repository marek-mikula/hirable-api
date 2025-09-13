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
        return CompanyContact::query()
            ->select(['id', 'firstname', 'lastname', 'company_name'])
            ->whereCompany($user->company_id)
            ->when($data->hasQuery(), function (Builder $query) use ($data): void {
                $query->where(function (Builder $query) use ($data): void {
                    $words = $data->getQueryWords();

                    foreach ($words as $word) {
                        if (is_numeric($word)) {
                            $query->orWhere('id', $word);
                        }

                        $query
                            ->orWhere('firstname', 'like', sprintf('%%%s%%', $word))
                            ->orWhere('lastname', 'like', sprintf('%%%s%%', $word))
                            ->orWhere('email', 'like', sprintf('%%%s%%', $word))
                            ->orWhere('company_name', 'like', sprintf('%%%s%%', $word));
                    }
                });
            })
            ->limit($data->limit)
            ->get()
            ->map(static fn (CompanyContact $item): \Domain\Search\Data\ResultData => ResultData::from([
                'value' => $item->id,
                'label' => $item->label,
            ]));
    }
}
