<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use App\Enums\ResponseCodeEnum;
use App\Exceptions\HttpException;

use function PHPUnit\Framework\assertSame;

function assertException(\Closure $closure, \Closure $assert): void
{
    try {
        call_user_func($closure);
    } catch (\Exception $exception) {
        call_user_func($assert, $exception);

        return;
    }

    fail('Exception was not thrown in given closure.');
}

function assertHttpException(\Closure $closure, \Closure|ResponseCodeEnum $assert): void
{
    try {
        call_user_func($closure);
    } catch (HttpException $httpException) {
        if ($assert instanceof \Closure) {
            call_user_func($assert, $httpException);
        } else {
            assertSame($assert, $httpException->getResponseCode());
        }

        return;
    }

    fail('HttpException was not thrown in given closure.');
}
