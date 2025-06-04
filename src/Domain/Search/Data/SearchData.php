<?php

declare(strict_types=1);

namespace Domain\Search\Data;

use Spatie\LaravelData\Data;

class SearchData extends Data
{
    public ?string $query = null;

    public int $limit;

    public function hasQuery(): bool
    {
        return !empty($this->query);
    }

    public function getQueryWords(): array
    {
        if (!$this->hasQuery()) {
            return [];
        }

        return str((string) $this->query)
            ->trim()
            ->explode(',')
            ->filter()
            ->map(fn (string $word) => trim($word))
            ->all();
    }
}
