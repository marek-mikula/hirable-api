<?php

namespace App\Jobs;

use App\Enums\QueueEnum;

abstract class ScheduleJob extends Job
{
    public function __construct()
    {
        $this->onQueue(QueueEnum::SCHEDULE->value);
    }
}
