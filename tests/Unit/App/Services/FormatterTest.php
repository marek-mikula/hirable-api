<?php

namespace Tests\Unit\App\Services;

use App\Services\Formatter;

use function PHPUnit\Framework\assertSame;

/** @covers \App\Services\Formatter::time */
it('correctly formats time', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.time', 'H:i');
    config()->set('format.time_seconds', 'H:i:s');

    $time = now()->setTime(13, 45, 23);

    assertSame('-', $formatter->time(null));
    assertSame('13:45', $formatter->time($time));
    assertSame('13:45:23', $formatter->time($time, withSeconds: true));
});

/** @covers \App\Services\Formatter::date */
it('correctly formats date', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.date', 'Y-m-d');

    $time = now()->setDate(1999, 2, 21);

    assertSame('-', $formatter->date(null));
    assertSame('1999-02-21', $formatter->date($time));
});

/** @covers \App\Services\Formatter::datetime */
it('correctly formats datetime', function (): void {
    /** @var Formatter $formatter */
    $formatter = app(Formatter::class);

    config()->set('format.datetime', 'Y-m-d H:i');
    config()->set('format.datetime_seconds', 'Y-m-d H:i:s');

    $time = now()
        ->setTime(13, 45, 23)
        ->setDate(1999, 2, 21);

    assertSame('-', $formatter->datetime(null));
    assertSame('1999-02-21 13:45', $formatter->datetime($time));
    assertSame('1999-02-21 13:45:23', $formatter->datetime($time, withSeconds: true));
});
