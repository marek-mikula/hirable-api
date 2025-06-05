<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use PHPUnit\Framework\Assert;

function fail(string $message): void
{
    Assert::fail($message);
}