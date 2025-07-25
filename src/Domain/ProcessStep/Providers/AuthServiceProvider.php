<?php

declare(strict_types=1);

namespace Domain\ProcessStep\Providers;

use Domain\ProcessStep\Models\ProcessStep;
use Domain\ProcessStep\Policies\ProcessStepPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ProcessStep::class => ProcessStepPolicy::class,
    ];
}
