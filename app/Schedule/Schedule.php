<?php

declare(strict_types=1);

namespace App\Schedule;

use App\Concerns\Resolvable;

abstract class Schedule
{
    use Resolvable;

    public static function call(mixed ...$args): void
    {
        app()->call([static::resolve(), '__invoke'], $args);
    }

    public static function closure(mixed ...$args): callable
    {
        return function () use ($args): void {
            static::call(...$args);
        };
    }
}
