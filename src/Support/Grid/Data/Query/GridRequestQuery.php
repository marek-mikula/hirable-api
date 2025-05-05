<?php

declare(strict_types=1);

namespace Support\Grid\Data\Query;

use Spatie\LaravelData\Data;
use Support\Grid\Enums\OrderEnum;
use Support\Grid\Enums\PerPageEnum;

class GridRequestQuery extends Data
{
    public int $page;

    public PerPageEnum $perPage;

    public ?string $searchQuery = null;

    /** @var array<string,OrderEnum> key is column key */
    public array $sort;

    public function hasSearchQuery(): bool
    {
        return !empty($this->searchQuery);
    }

    public function hasSort(): bool
    {
        return !empty($this->sort);
    }

    public function hasSortKey(string $key): bool
    {
        return array_key_exists($key, $this->sort);
    }
}
