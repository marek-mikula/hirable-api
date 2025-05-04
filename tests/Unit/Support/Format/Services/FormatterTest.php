<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Format\Services;

use Support\Format\Services\Formatter;

use function PHPUnit\Framework\assertSame;

/** @covers \Support\Format\Services\Formatter::formatTime */
it('correctly formats time', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.time', 'H:i');
    config()->set('format.time_seconds', 'H:i:s');

    $time = now()->setTime(13, 45, 23);

    assertSame('-', $formatter->formatTime(null));
    assertSame('13:45', $formatter->formatTime($time));
    assertSame('13:45:23', $formatter->formatTime($time, withSeconds: true));
});

/** @covers \Support\Format\Services\Formatter::formatDate */
it('correctly formats date', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.date', 'Y-m-d');

    $time = now()->setDate(1999, 2, 21);

    assertSame('-', $formatter->formatDate(null));
    assertSame('1999-02-21', $formatter->formatDate($time));
});

/** @covers \Support\Format\Services\Formatter::formatDatetime */
it('correctly formats datetime', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.datetime', 'Y-m-d H:i');
    config()->set('format.datetime_seconds', 'Y-m-d H:i:s');

    $time = now()
        ->setTime(13, 45, 23)
        ->setDate(1999, 2, 21);

    assertSame('-', $formatter->formatDatetime(null));
    assertSame('1999-02-21 13:45', $formatter->formatDatetime($time));
    assertSame('1999-02-21 13:45:23', $formatter->formatDatetime($time, withSeconds: true));
});
