<?php

namespace App\Concerns;

trait Resolvable
{
    public static function resolve(): static
    {
        return app(static::class);
    }
}