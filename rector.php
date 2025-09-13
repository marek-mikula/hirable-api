<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/bootstrap',
        __DIR__ . '/config',
        __DIR__ . '/lang',
        __DIR__ . '/routes',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRules([
        // these rules add static keyword to the
        // factory state methods, which makes the
        // broken, because of Laravel bug.
        // @see https://github.com/laravel/framework/issues/51839
        // StaticArrowFunctionRector::class,
        // StaticClosureRector::class,
    ])
    ->withSkipPath(__DIR__ . '/bootstrap/cache')
    ->withPhpSets()
    ->withTypeCoverageLevel(10)
    ->withDeadCodeLevel(10)
    ->withCodeQualityLevel(10)
    ->withCodingStyleLevel(10);
