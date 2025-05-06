<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Repositories\CompanyBenefitRepository;
use Domain\Company\Repositories\CompanyBenefitRepositoryInterface;
use Domain\Company\Repositories\CompanyRepository;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(CompanyBenefitRepositoryInterface::class, CompanyBenefitRepository::class);
    }

    public function provides(): array
    {
        return [
            CompanyRepositoryInterface::class,
            CompanyBenefitRepositoryInterface::class,
        ];
    }
}
