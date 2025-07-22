<?php

declare(strict_types=1);

namespace Domain\Candidate\Providers;

use Domain\Candidate\Repositories\CandidateRepository;
use Domain\Candidate\Repositories\CandidateRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider as BaseDeferrableProvider;
use Illuminate\Support\ServiceProvider;

class DeferrableServiceProvider extends ServiceProvider implements BaseDeferrableProvider
{
    public function register(): void
    {
        $this->app->bind(CandidateRepositoryInterface::class, CandidateRepository::class);
    }

    public function provides(): array
    {
        return [
            CandidateRepositoryInterface::class,
        ];
    }
}
