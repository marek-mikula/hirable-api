<?php

namespace Support\Grid\Data\Definition;

use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;
use Support\Grid\Enums\GridEnum;
use Support\Grid\Enums\PerPageEnum;

class GridDefinition extends Data
{
    /**
     * @param  GridColumnDefinition[]  $columns
     * @param  GridActionDefinition[]  $actions
     */
    public function __construct(
        public GridEnum $identifier,
        public string $keyAttribute,
        public array $columns,
        public array $actions,
        public bool $allowSearch,
        public bool $allowSettings,
        public bool $allowFilter,
        public PerPageEnum $perPage = PerPageEnum::HUNDRED,
        public bool $stickyHeader = true,
        public bool $stickyFooter = true,
    ) {
    }

    public function getColumn(string $key): ?GridColumnDefinition
    {
        /** @var GridColumnDefinition|null $column */
        $column = Arr::first($this->columns, fn (GridColumnDefinition $item) => $item->key === $key);

        return $column;
    }

    /**
     * @return string[]
     */
    public function getAvailableColumnKeys(): array
    {
        return Arr::pluck($this->columns, 'key');
    }
}
