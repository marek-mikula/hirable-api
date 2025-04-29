<?php

declare(strict_types=1);

namespace App\Schedule;

abstract class Schedule
{
    public static function call(mixed ...$args): void
    {
        app()->call([new static(), '__invoke'], $args);
    }

    public static function closure(mixed ...$args): callable
    {
        return function () use ($args): void {
            static::call(...$args);
        };
    }
}
