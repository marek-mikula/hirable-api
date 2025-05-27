<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\File\Repositories\FileRepository;
use Support\File\Repositories\FileRepositoryInterface;
use Support\File\Repositories\ModelHasFilesRepository;
use Support\File\Repositories\ModelHasFilesRepositoryInterface;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(ModelHasFilesRepositoryInterface::class, ModelHasFilesRepository::class);
    }

    public function provides(): array
    {
        return [
            FileRepositoryInterface::class,
            ModelHasFilesRepositoryInterface::class,
        ];
    }
}
