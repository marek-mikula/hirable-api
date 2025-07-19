<?php

declare(strict_types=1);

namespace Services\OpenAI\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/openai.php', 'openai');
    }
}
