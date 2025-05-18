<?php

declare(strict_types=1);

namespace Domain\Position\Providers;

use Domain\Position\Repositories\PositionSuggestRepository;
use Domain\Position\Repositories\PositionSuggestRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(PositionSuggestRepositoryInterface::class, PositionSuggestRepository::class);
    }

    public function provides(): array
    {
        return [
            PositionSuggestRepositoryInterface::class
        ];
    }
}
