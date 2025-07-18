<?php

declare(strict_types=1);

namespace Domain\Application\Providers;

use Domain\Application\Repositories\ApplicationRepository;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
    }

    public function provides(): array
    {
        return [];
    }
}
