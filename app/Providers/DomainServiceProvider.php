<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    private array $providers = [
        \Domain\Application\Providers\ServiceProvider::class,
        \Domain\Auth\Providers\ServiceProvider::class,
        \Domain\Register\Providers\ServiceProvider::class,
        \Domain\Password\Providers\ServiceProvider::class,
        \Domain\Verification\Providers\ServiceProvider::class,
        \Domain\Company\Providers\ServiceProvider::class,
        \Domain\Search\Providers\ServiceProvider::class,
        \Domain\Candidate\Providers\ServiceProvider::class,
        \Domain\User\Providers\ServiceProvider::class,
        \Domain\Position\Providers\ServiceProvider::class,
        \Domain\Notification\Providers\ServiceProvider::class,
    ];

    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        //
    }
}
