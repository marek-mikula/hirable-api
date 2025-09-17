<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CapitalizeCast extends Cast
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) && !empty($value) ? Str::ucfirst($value) : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_string($value) && !empty($value) ? Str::ucfirst($value) : $value;
    }
}
