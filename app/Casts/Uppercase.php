<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Uppercase implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) && !empty($value) ? Str::upper($value) : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) && !empty($value) ? Str::upper($value) : $value;
    }
}
