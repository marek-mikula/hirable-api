<?php

declare(strict_types=1);

namespace Tests\Common\Data;

use Spatie\LaravelData\Data;

class TestData extends Data
{
    public ?string $firstname = null;

    public ?string $lastname = null;
}
