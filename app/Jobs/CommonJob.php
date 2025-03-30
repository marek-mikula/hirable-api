<?php

namespace App\Jobs;

use App\Enums\QueueEnum;

abstract class CommonJob extends Job
{
    public function __construct()
    {
        $this->onQueue(QueueEnum::COMMON->value);
    }
}
