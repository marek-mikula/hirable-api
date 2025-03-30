<?php

namespace Support\Grid\UseCases;

use App\Models\User;
use App\UseCases\UseCase;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Data\Query\GridQuery;
use Support\Grid\Data\Settings\GridSetting;

class GetGridQueryUseCase extends UseCase
{
    public function handle(User $user, GridDefinition $definition, ?GridSetting $setting): GridQuery
    {
        $query = GridQuery::from([
            'page' => null, // we do not store page yet, leave it null
            'searchQuery' => null,
            'sort' => [],
        ]);

        if (! $setting) {
            return $query;
        }

        return $this->mergeSettingsIntoQuery($definition, $query, $setting);
    }

    private function mergeSettingsIntoQuery(GridDefinition $definition, GridQuery $query, GridSetting $setting): GridQuery
    {
        if ($definition->allowSearch) {
            $query->searchQuery = $setting->searchQuery;
        }

        $sort = [];

        foreach ($setting->sort as $key => $order) {
            $columnDefinition = $definition->getColumn($key);

            // column was probably removed from the definition => skip
            if (! $columnDefinition) {
                continue;
            }

            // column does not allow sort => skip
            if (! $columnDefinition->allowSort) {
                continue;
            }

            // column is disabled => skip
            if (! $columnDefinition->enabled) {
                continue;
            }

            $sort[$key] = $order;
        }

        $query->sort = $sort;

        return $query;
    }
}
