<?php

declare(strict_types=1);

namespace Domain\Search\UseCases;

use App\UseCases\UseCase;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\Position\Enums\PositionStateEnum;
use Domain\Position\Models\Builders\PositionBuilder;
use Domain\Position\Models\Position;
use Domain\Search\Data\ResultData;
use Domain\Search\Data\SearchData;
use Domain\User\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;

class SearchAdvertisementPositionsUseCase extends UseCase
{
    /**
     * @return Collection<ResultData>
     */
    public function handle(User $user, SearchData $data): Collection
    {
        return Position::query()
            ->select(['id', 'name'])
            ->where('state', PositionStateEnum::OPENED->value)
            ->whereCompany($user->company_id)
            ->where(function (PositionBuilder $query) use ($user) {
                $query
                    // my own positions
                    ->where('user_id', $user->id)

                    // positions where I am hiring manager
                    ->orWhereExists(function (Builder $query) use ($user): void {
                        $query
                            ->selectRaw(1)
                            ->from('model_has_positions')
                            ->whereColumn('model_has_positions.position_id', 'positions.id')
                            ->where('model_has_positions.model_type', User::class)
                            ->where('model_has_positions.model_id', $user->id)
                            ->where('model_has_positions.role', PositionRoleEnum::HIRING_MANAGER->value);
                    });
            })
            ->when($data->hasQuery(), function (PositionBuilder $query) use ($data): void {
                $query->where(function (PositionBuilder $query) use ($data): void {
                    $words = $data->getQueryWords();

                    foreach ($words as $word) {
                        if (is_numeric($word)) {
                            $query->orWhere('id', $word);
                        }

                        $query
                            ->orWhere('name', 'like', "%{$word}%");
                    }
                });
            })
            ->limit($data->limit)
            ->get()
            ->map(static fn (Position $item) => ResultData::from([
                'value' => $item->id,
                'label' => $item->name,
            ]));
    }
}
