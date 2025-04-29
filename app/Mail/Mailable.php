<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Mail\Mailable as BaseMailable;
use Illuminate\Queue\SerializesModels;

abstract class Mailable extends BaseMailable
{
    use SerializesModels;
}
