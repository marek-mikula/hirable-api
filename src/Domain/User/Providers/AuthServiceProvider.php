<?php

declare(strict_types=1);

namespace Domain\User\Providers;

use Domain\User\Models\User;
use Domain\User\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];
}
