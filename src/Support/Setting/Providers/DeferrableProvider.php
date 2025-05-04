<?php

declare(strict_types=1);

namespace Support\Setting\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Setting\Repositories\SettingRepository;
use Support\Setting\Repositories\SettingRepositoryInterface;

class DeferrableProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
    }

    public function provides(): array
    {
        return [
            SettingRepositoryInterface::class,
        ];
    }
}
