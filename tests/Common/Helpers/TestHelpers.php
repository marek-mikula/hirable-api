<?php

namespace Tests\Common\Helpers;

use PHPUnit\Framework\Assert;

function fail(string $message): void
{
    Assert::fail($message);
}
