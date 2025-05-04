<?php

declare(strict_types=1);

namespace Domain\User\Providers;

use Domain\User\Repositories\UserRepository;
use Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function provides(): array
    {
        return [
            UserRepositoryInterface::class,
        ];
    }
}
