<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\File\Repositories\FileRepository;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\ModelHasFileRepository;
use Support\File\Repositories\ModelHasFileRepositoryInterface;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(ModelHasFileRepositoryInterface::class, ModelHasFileRepository::class);
    }

    public function provides(): array
    {
        return [
            FileRepositoryInterface::class,
            ModelHasFileRepositoryInterface::class,
        ];
    }
}
