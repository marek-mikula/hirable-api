<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Models\Builders\ModelHasPositionBuilder;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Domain\User\Models\Builders\UserBuilder;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchPositionUsersUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, Position $position, SearchData $data, bool $ignoreAuth, Collection $roles): Collection
    {
        return User::query()
            ->select(['id', 'firstname', 'lastname'])
            ->whereCompany($user->company_id)
            ->where(function (UserBuilder $query) use (
                $position,
                $roles
            ): void {
                $query
                    ->whereHas('ownsPositions', function (PositionBuilder $query) use (
                        $position,
                    ): void {
                        $query->where('id', $position->id);
                    })
                    ->orWhereHas('positionModels', function (ModelHasPositionBuilder $query) use (
                        $position,
                        $roles,
                    ): void {
                        $query->where('position_id', $position->id);

                        if ($roles->isNotEmpty()) {
                            $query->whereIn('role', $roles);
                        }
                    });
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
