<?php

namespace App\Models\Builders;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as BaseBuilder;

abstract class Builder extends BaseBuilder
{
    public function whereTimestamp(string $column, string $operator, Carbon $timestamp): static
    {
        return $this->where($column, $operator, $timestamp->format('Y-m-d H:i:s'));
    }

    public function whereTrue(string $column): static
    {
        return $this->where($column, '=', 1);
    }

    public function whereFalse(string $column): static
    {
        return $this->where($column, '=', 0);
    }
}
