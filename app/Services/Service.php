<?php

declare(strict_types=1);

namespace App\Services;

abstract class Service
{
    public static function resolve(): static
    {
        return app(static::class);
    }
}
