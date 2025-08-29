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
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
    ])
    ->withSkipPath(__DIR__ . '/bootstrap/cache')
    ->withPhpSets()
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0)
    ->withCodingStyleLevel(0);
