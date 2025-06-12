<?php

declare(strict_types=1);

namespace Domain\Password\Providers;

use Domain\Password\Events\PasswordChanged;
use Domain\Password\Listeners\SendPasswordChangedEmailListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PasswordChanged::class => [
            SendPasswordChangedEmailListener::class
        ],
    ];
}
