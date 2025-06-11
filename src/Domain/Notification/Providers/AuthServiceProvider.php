<?php

declare(strict_types=1);

namespace Domain\Notification\Providers;

use Domain\Notification\Models\Notification;
use Domain\Notification\Policies\NotificationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Notification::class => NotificationPolicy::class,
    ];
}
