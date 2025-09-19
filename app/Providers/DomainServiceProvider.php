<?php

declare(strict_types=1);

namespace App\Providers;

use App\Enums\EnvEnum;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    private array $domainProviders = [
        \Domain\AI\Providers\ServiceProvider::class,
        \Domain\Application\Providers\ServiceProvider::class,
        \Domain\Auth\Providers\ServiceProvider::class,
        \Domain\Register\Providers\ServiceProvider::class,
        \Domain\Password\Providers\ServiceProvider::class,
        \Domain\Verification\Providers\ServiceProvider::class,
        \Domain\Company\Providers\ServiceProvider::class,
        \Domain\Search\Providers\ServiceProvider::class,
        \Domain\Candidate\Providers\ServiceProvider::class,
        \Domain\User\Providers\ServiceProvider::class,
        \Domain\Position\Providers\ServiceProvider::class,
        \Domain\Notification\Providers\ServiceProvider::class,
        \Domain\ProcessStep\Providers\ServiceProvider::class,
    ];

    private array $supportProviders = [
        \Support\ActivityLog\Providers\ServiceProvider::class,
        \Support\File\Providers\ServiceProvider::class,
        \Support\Grid\Providers\ServiceProvider::class,
        \Support\Setting\Providers\ServiceProvider::class,
        \Support\Token\Providers\ServiceProvider::class,
        \Support\Classifier\Providers\ServiceProvider::class,
        \Support\Format\Providers\ServiceProvider::class,
    ];

    private array $AIProviders = [
        \AIProviders\OpenAI\Providers\ServiceProvider::class,
    ];

    public function register(): void
    {
        $providers = array_merge(
            $this->domainProviders,
            $this->supportProviders,
            $this->AIProviders,
        );

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }

        if (!isEnv(EnvEnum::PRODUCTION, EnvEnum::TESTING)) {
            $this->app->register(\Support\NotificationPreview\Providers\ServiceProvider::class);
        }
    }

    public function boot(): void
    {
        //
    }
}
