<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Classifier\Repositories;

use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;

use function Tests\Common\Helpers\assertCollectionsAreSame;

uses()->beforeEach(function (): void {
    // turn off caching of classifiers, so we
    // know that we use DB repository
    config()->set('classifier.cache_enabled', false);
});

/** @covers \Support\Classifier\Repositories\ClassifierRepository::getValuesForType */
it('tests getValuesForType method', function (): void {
    /** @var ClassifierRepositoryInterface $repository */
    $repository = app(ClassifierRepositoryInterface::class);

    $benefits = Classifier::factory()
        ->ofType(ClassifierTypeEnum::BENEFIT)
        ->count(2)
        ->create();

    $currencies = Classifier::factory()
        ->ofType(ClassifierTypeEnum::CURRENCY)
        ->count(2)
        ->create();

    assertCollectionsAreSame($benefits, $repository->getValuesForType(ClassifierTypeEnum::BENEFIT));
    assertCollectionsAreSame($currencies, $repository->getValuesForType(ClassifierTypeEnum::CURRENCY));
});

/** @covers \Support\Classifier\Repositories\ClassifierRepository::getValuesForTypes */
it('tests getValuesForTypes method', function (): void {
    /** @var ClassifierRepositoryInterface $repository */
    $repository = app(ClassifierRepositoryInterface::class);

    $benefits = Classifier::factory()
        ->ofType(ClassifierTypeEnum::BENEFIT)
        ->count(2)
        ->create();

    $currencies = Classifier::factory()
        ->ofType(ClassifierTypeEnum::CURRENCY)
        ->count(2)
        ->create();

    $result = $repository->getValuesForTypes([
        ClassifierTypeEnum::BENEFIT,
        ClassifierTypeEnum::CURRENCY,
    ]);

    assertCollectionsAreSame($benefits, $result[ClassifierTypeEnum::BENEFIT->value]);
    assertCollectionsAreSame($currencies, $result[ClassifierTypeEnum::CURRENCY->value]);
});
