<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\QueueEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

abstract class QueuedListener extends Listener implements ShouldQueue, ShouldQueueAfterCommit
{
    public string $queue = QueueEnum::COMMON->value;
}
