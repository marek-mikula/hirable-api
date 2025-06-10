<?php

declare(strict_types=1);

namespace Domain\Company\Providers;

use Domain\Company\Models\Company;
use Domain\Company\Policies\CompanyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Company::class => CompanyPolicy::class,
    ];
}
