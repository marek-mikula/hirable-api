<?php

declare(strict_types=1);

namespace Support\Grid\Data\Settings;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;
use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;

class GridSetting extends Data
{
    public ?PerPageEnum $perPage = null;

    public ?bool $stickyHeader = null;

    public ?bool $stickyFooter = null;

    /** @var array<string,GridColumnSetting> key is the column key */
    public array $columns;

    /** @var string[] list of column keys */
    public array $order;

    public ?string $searchQuery = null;

    /** @var array<string,OrderEnum> key is the column key */
    public array $sort;

    public function getColumn(string $key): GridColumnSetting
    {
        /** @var GridColumnSetting $setting */
        $setting = Arr::get($this->columns, $key, fn () => GridColumnSetting::from([
            'enabled' => null,
            'width' => null,
        ]));

        return $setting;
    }

    public function setColumnWidth(string $key, ?int $width): void
    {
        $setting = $this->getColumn($key);

        $setting->width = $width;

        Arr::set($this->columns, $key, $setting);
    }

    public function setColumnState(string $key, ?bool $enabled): void
    {
        $setting = $this->getColumn($key);

        $setting->enabled = $enabled;

        Arr::set($this->columns, $key, $setting);
    }
}
