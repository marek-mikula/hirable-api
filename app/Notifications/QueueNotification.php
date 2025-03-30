<?php

namespace App\Notifications;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class QueueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue(QueueEnum::NOTIFICATIONS->value);
    }
}
