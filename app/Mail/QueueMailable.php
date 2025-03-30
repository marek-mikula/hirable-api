<?php

namespace App\Mail;

use App\Enums\QueueEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class QueueMailable extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onQueue(QueueEnum::NOTIFICATIONS->value);
    }
}
