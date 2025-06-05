<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Classifier\Repositories;

use Support\Classifier\Repositories\ClassifierRepositoryInterface;

uses()->beforeEach(function (): void {
    // turn on caching of classifiers, so we
    // know that we use cached repository
    config()->set('classifier.cache_enabled', true);
});

/** @covers \Support\Classifier\Repositories\ClassifierRepository::getValuesForType */
it('tests getValuesForType method', function (): void {
    /** @var ClassifierRepositoryInterface $repository */
    $repository = app(ClassifierRepositoryInterface::class);

    // todo
})->todo();

/** @covers \Support\Classifier\Repositories\ClassifierRepository::getValuesForTypes */
it('tests getValuesForTypes method', function (): void {
    /** @var ClassifierRepositoryInterface $repository */
    $repository = app(ClassifierRepositoryInterface::class);

    // todo
})->todo();
