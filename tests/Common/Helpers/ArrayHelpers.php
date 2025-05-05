<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use function PHPUnit\Framework\assertSame;

function assertArraysAreSame(array $expected, array $actual): void
{
    assertSame(sort($expected), sort($actual));
}
