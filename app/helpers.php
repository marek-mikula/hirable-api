<?php

declare(strict_types=1);

use App\Enums\EnvEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Str;
use Support\Format\Services\Formatter;
use Support\Notification\Enums\NotificationTypeEnum;

if (!function_exists('isEnv')) {
    function isEnv(EnvEnum ...$environments): bool
    {
        return app()->environment(...collect($environments)->pluck('value')->all());
    }
}

if (!function_exists('telescopeEnabled')) {
    function telescopeEnabled(): bool
    {
        // do not boot Telescope on production or testing environment, never
        return !isEnv(EnvEnum::PRODUCTION, EnvEnum::TESTING) && config('telescope.enabled');
    }
}

if (!function_exists('frontendLink')) {
    function frontendLink(string $uri, array $params = []): string
    {
        $frontEndUrl = (string) config('app.frontend_url');

        return collect($params)->reduce(fn (string $url, mixed $value, string $param): string => Str::replace(sprintf('{%s}', $param), (string) $value, $url), buildUrl([$frontEndUrl, $uri]));
    }
}

if (!function_exists('buildUrl')) {
    /**
     * @param  string[]  $parts
     */
    function buildUrl(array $parts): string
    {
        return collect($parts)
            ->map(function (string $part): string {
                if (Str::startsWith($part, '/')) {
                    $part = Str::after($part, '/');
                }

                if (Str::endsWith($part, '/')) {
                    $part = Str::beforeLast($part, '/');
                }

                return $part;
            })
            ->implode('/');
    }
}

if (!function_exists('__n')) {
    function __n(
        NotificationTypeEnum $type,
        string $channel,
        string $key,
        array $replace = [],
        ?string $locale = null
    ): array|string {
        return __(sprintf('notifications.%s.%s.%s', $type->value, $channel, $key), $replace, $locale);
    }
}

if (!function_exists('formatter')) {
    function formatter(): Formatter
    {
        return app(Formatter::class);
    }
}

if (!function_exists('collectEnum')) {
    /**
     * @template TEnumClass of BackedEnum
     *
     * @param  class-string<TEnumClass>  $enum
     *
     * @returns Collection<TEnumClass>
     */
    function collectEnum(array|string|int $items, string $enum): Collection
    {
        return collect(Arr::wrap($items))->map(static fn (mixed $item) => $enum::from($item));
    }
}

if (!function_exists('usesSoftDeletes')) {
    function usesSoftDeletes(string $class): bool
    {
        return (bool) once(static fn () => collect(class_uses_recursive($class))->contains(SoftDeletes::class));
    }
}

if (!function_exists('injectClosure')) {
    function injectClosure(callable $closure): callable
    {
        return fn () => app()->call($closure);
    }
}

if (!function_exists('modelCollection')) {
    /**
     * @template T of Model
     * @param class-string<T> $model
     * @param T[] $models
     * @return EloquentCollection<T>
     */
    function modelCollection(string $model, array $models = []): EloquentCollection
    {
        /** @var Model $model */
        $model = new $model();

        return $model->newCollection($models);
    }
}
