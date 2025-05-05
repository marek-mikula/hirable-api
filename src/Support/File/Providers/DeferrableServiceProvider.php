<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\File\Repositories\FileRepository;
use Support\File\Repositories\FileRepositoryInterface;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }

    public function provides(): array
    {
        return [
            FileRepositoryInterface::class,
        ];
    }
}
