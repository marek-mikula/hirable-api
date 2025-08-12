<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Providers;

use Domain\ProcessStep\Repositories\ProcessStepRepository;
use Domain\ProcessStep\Repositories\ProcessStepRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(ProcessStepRepositoryInterface::class, ProcessStepRepository::class);
    }

    public function provides(): array
    {
        return [
            ProcessStepRepositoryInterface::class,
        ];
    }
}
