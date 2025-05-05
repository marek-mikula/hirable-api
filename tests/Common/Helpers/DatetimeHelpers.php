<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use Carbon\Carbon;

use function PHPUnit\Framework\assertSame;

function assertDatetime(?Carbon $expected, ?Carbon $actual): void
{
    $format = 'Y-m-d H:i:s';

    $expected = $expected?->format($format) ?? '';
    $actual = $actual?->format($format) ?? '';

    $message = sprintf(
        'Given Carbon value "%s" do not match "%s" in format "%s".',
        $actual,
        $expected,
        $format,
    );

    assertSame($expected, $actual, message: $message);
}

function assertDate(?Carbon $expected, ?Carbon $actual): void
{
    $format = 'Y-m-d';

    $expected = $expected?->format($format) ?? '';
    $actual = $actual?->format($format) ?? '';

    $message = sprintf(
        'Given Carbon value "%s" do not match "%s" in format "%s".',
        $actual,
        $expected,
        $format,
    );

    assertSame($expected, $actual, message: $message);
}

function assertTime(?Carbon $expected, ?Carbon $actual, bool $withSeconds = true): void
{
    $format = $withSeconds ? 'H:i:s' : 'H:i';

    $expected = $expected?->format($format) ?? '';
    $actual = $actual?->format($format) ?? '';

    $message = sprintf(
        'Given Carbon value "%s" do not match "%s" in format "%s".',
        $actual,
        $expected,
        $format,
    );

    assertSame($expected, $actual, message: $message);
}
