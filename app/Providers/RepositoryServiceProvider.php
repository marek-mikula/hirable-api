<?php

namespace App\Providers;

use App\Repositories\Company\CompanyRepository;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\File\FileRepository;
use App\Repositories\File\FileRepositoryInterface;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\Token\TokenRepository;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /** @var array<class-string, class-string> */
    private array $repositories = [
        FileRepositoryInterface::class => FileRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        TokenRepositoryInterface::class => TokenRepository::class,
        CompanyRepositoryInterface::class => CompanyRepository::class,
        SettingRepositoryInterface::class => SettingRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->repositories as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }

    public function boot(): void
    {
        //
    }

    public function provides(): array
    {
        return array_keys($this->repositories);
    }
}
