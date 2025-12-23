<?php

declare(strict_types=1);

namespace App\Concerns;

trait Resolvable
{
    public static function resolve(): static
    {
        return app(static::class);
    }
}
