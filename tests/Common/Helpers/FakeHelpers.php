<?php

declare(strict_types=1);

namespace Tests\Common\Helpers;

use Carbon\Carbon;

function fakeDate(): Carbon
{
    return Carbon::createFromFormat('Y-m-d', fake()->date);
}
