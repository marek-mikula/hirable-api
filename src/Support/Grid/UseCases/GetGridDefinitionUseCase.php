<?php

declare(strict_types=1);

namespace Support\Grid\UseCases;

use App\UseCases\UseCase;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Support\Grid\Data\Definition\GridColumnDefinition;
use Support\Grid\Data\Definition\GridDefinition;
use Support\Grid\Data\Settings\GridColumnSetting;
use Support\Grid\Data\Settings\GridSetting;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Grids\Grid;

class GetGridDefinitionUseCase extends UseCase
{
    public function handle(User $user, GridEnum $grid, ?GridSetting $setting): GridDefinition
    {
        /** @var Grid $instance */
        $instance = app($grid->getClass());

        $definition = $instance->getDefinition($user);

        // grid does not allow user
        // to change the settings
        if (!$definition->allowSettings) {
            return $definition;
        }

        if (!$setting) {
            return $definition;
        }

        return $this->mergeSettingsIntoDefinition($definition, $setting);
    }

    private function mergeSettingsIntoDefinition(GridDefinition $definition, GridSetting $setting): GridDefinition
    {
        if ($setting->perPage !== null) {
            $definition->perPage = $setting->perPage;
        }

        if ($setting->stickyHeader !== null) {
            $definition->stickyHeader = $setting->stickyHeader;
        }

        if ($setting->stickyFooter !== null) {
            $definition->stickyFooter = $setting->stickyFooter;
        }

        $this->mergeColumns($definition, $setting->columns);

        if (!empty($setting->order)) {
            $this->orderColumns($definition, $setting->order);
        }

        return $definition;
    }

    /**
     * @param  array<string,GridColumnSetting>  $columns
     */
    private function mergeColumns(GridDefinition $definition, array $columns): void
    {
        foreach ($columns as $key => $column) {
            $columnDefinition = $definition->getColumn($key);

            // column was probably removed from the definition => skip
            if (!$columnDefinition) {
                continue;
            }

            if ($column->width !== null) {
                $columnDefinition->width = $this->resolveColumnWidth($columnDefinition, $column);
            }

            if ($column->enabled !== null) {
                $columnDefinition->enabled = $column->enabled;
            }
        }
    }

    /**
     * @param  string[]  $order
     */
    private function orderColumns(GridDefinition $definition, array $order): void
    {
        $missingColumns = [];

        // new columns were probably added and user
        // had some order saved from the past
        if (count($order) !== count($definition->columns)) {
            $missingColumns = Arr::where($definition->columns, static fn (GridColumnDefinition $column): bool => !in_array($column->key, $order));
        }

        if (empty($missingColumns)) {
            $columns = $definition->columns;
        } else {
            $columns = Arr::where($definition->columns, static fn (GridColumnDefinition $column): bool => in_array($column->key, $order));
        }

        // sort only those columns, which are
        // in the order array
        uasort($columns, static fn (GridColumnDefinition $a, GridColumnDefinition $b): int => array_search($a->key, $order) <=> array_search($b->key, $order));

        // append missing columns to the end
        // because we have no way how to sort
        // them
        $definition->columns = [...$columns, ...$missingColumns];
    }

    private function resolveColumnWidth(GridColumnDefinition $column, GridColumnSetting $setting): int
    {
        if ($column->minWidth !== null && $setting->width < $column->minWidth) {
            return (int) $column->minWidth;
        }

        if ($column->maxWidth !== null && $setting->width > $column->maxWidth) {
            return (int) $column->maxWidth;
        }

        return (int) $setting->width;
    }
}
