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

    public function getFulltextQuery(): ?string
    {
        if (!$this->hasQuery()) {
            return null;
        }

        return str((string) $this->query)
            ->trim()
            ->explode(' ')
            ->map(static fn (string $word) => "*{$word}*")
            ->join(',');
    }

    public function getNumericItems(): array
    {
        if (!$this->hasQuery()) {
            return [];
        }

        return str((string) $this->query)
            ->trim()
            ->explode(' ')
            ->filter(static fn (string $word) => preg_match('/^[0-9]+$/', $word))
            ->map(static fn (string $word) => intval($word))
            ->all();
    }
}
