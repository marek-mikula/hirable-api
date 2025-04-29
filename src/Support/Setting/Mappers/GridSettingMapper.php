<?php

declare(strict_types=1);

namespace Support\Setting\Mappers;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;
use Support\Grid\Data\Settings\GridColumnSetting;
use Support\Grid\Data\Settings\GridSetting;
use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;
use Support\Setting\Contracts\SettingMapper;

class GridSettingMapper implements SettingMapper
{
    /**
     * @return GridSetting
     */
    public function fromArray(array $data): Data
    {
        $perPage = Arr::get($data, 'perPage');
        $stickyHeader = Arr::get($data, 'stickyHeader');
        $stickyFooter = Arr::get($data, 'stickyFooter');
        $columns = Arr::get($data, 'columns', []);
        $order = Arr::get($data, 'order', []);
        $searchQuery = Arr::get($data, 'searchQuery');
        $sort = Arr::get($data, 'sort', []);

        return GridSetting::from([
            'perPage' => $perPage !== null ? PerPageEnum::tryFrom($perPage) : null,
            'stickyHeader' => $stickyHeader !== null ? (bool) $stickyHeader : null,
            'stickyFooter' => $stickyFooter !== null ? (bool) $stickyFooter : null,
            'columns' => is_array($columns) ? array_map([$this, 'mapColumn'], $columns) : [],
            'order' => is_array($order) ? array_values($order) : [],
            'searchQuery' => empty($searchQuery) ? null : (string) $searchQuery,
            'sort' => is_array($sort) ? array_filter(array_map([$this, 'mapSort'], $sort)) : [],
        ]);
    }

    /**
     * @param  GridSetting  $data
     */
    public function toArray(Data $data): array
    {
        $keepKeys = [];

        if ($data->perPage !== null) {
            $keepKeys[] = 'perPage';
        }

        if ($data->stickyHeader !== null) {
            $keepKeys[] = 'stickyHeader';
        }

        if ($data->stickyFooter !== null) {
            $keepKeys[] = 'stickyFooter';
        }

        $data->columns = collect($data->columns)
            ->filter(fn (GridColumnSetting $column) => $column->enabled !== null || $column->width !== null)
            ->all();

        if (!empty($data->columns)) {
            $keepKeys[] = 'columns';
        }

        if (!empty($data->searchQuery)) {
            $keepKeys[] = 'searchQuery';
        }

        if (!empty($data->sort)) {
            $keepKeys[] = 'sort';
        }

        return Arr::only($data->toArray(), $keepKeys);
    }

    private function mapColumn(array $column): GridColumnSetting
    {
        $enabled = Arr::get($column, 'enabled');
        $width = Arr::get($column, 'width');

        return GridColumnSetting::from([
            'enabled' => $enabled !== null ? (bool) $column['enabled'] : null,
            'width' => $width !== null ? (int) $column['width'] : null,
        ]);
    }

    private function mapSort(mixed $order): ?OrderEnum
    {
        return OrderEnum::tryFrom($order);
    }
}
