<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;

class EnumOrValueCast extends Cast
{
    /**
     * @param class-string<\BackedEnum> $enumClass
     */
    public function __construct(
        private readonly string $enumClass
    ) {
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $mappedValue = $this->enumClass::tryFrom($value);

        return $mappedValue instanceof $this->enumClass ? $mappedValue : $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof $this->enumClass) {
            $value = $value->value;
        }

        return $value;
    }
}
