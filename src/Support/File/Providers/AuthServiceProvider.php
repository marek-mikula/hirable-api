<?php

declare(strict_types=1);

namespace Support\File\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Support\File\Models\File;
use Support\File\Policies\FilePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        File::class => FilePolicy::class,
    ];
}
