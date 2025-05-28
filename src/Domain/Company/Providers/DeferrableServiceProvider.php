<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Repositories\CompanyContactRepository;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Company\Repositories\CompanyContactSuggestRepository;
use Domain\Company\Repositories\CompanyContactSuggestRepositoryInterface;
use Domain\Company\Repositories\CompanyRepository;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(CompanyContactRepositoryInterface::class, CompanyContactRepository::class);
        $this->app->bind(CompanyContactSuggestRepositoryInterface::class, CompanyContactSuggestRepository::class);
    }

    public function provides(): array
    {
        return [
            CompanyRepositoryInterface::class,
            CompanyContactRepositoryInterface::class,
            CompanyContactSuggestRepositoryInterface::class,
        ];
    }
}
