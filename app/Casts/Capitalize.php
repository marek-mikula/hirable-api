<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Capitalize implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) ? Str::ucfirst($value) : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) ? Str::ucfirst($value) : $value;
    }
}
