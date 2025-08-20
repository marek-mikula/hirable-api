<?php

declare(strict_types=1);

namespace Domain\Candidate\Providers;

use Domain\Candidate\Models\Candidate;
use Domain\Candidate\Policies\CandidatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Candidate::class => CandidatePolicy::class,
    ];
}
