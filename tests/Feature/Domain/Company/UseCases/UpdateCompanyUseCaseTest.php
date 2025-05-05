<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Company\UseCases;

use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Company\UseCases\UpdateCompanyUseCase;
use Domain\User\Models\User;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

use function PHPUnit\Framework\assertSame;
use function Tests\Common\Helpers\assertArraysAreSame;

/** @covers \Domain\Company\UseCases\UpdateCompanyUseCase::handle */
it('tests update company use case - all attributes', function (): void {
    $company = Company::factory()
        ->create();

    $user = User::factory()
        ->ofCompany($company, RoleEnum::ADMIN)
        ->create();

    $benefits = Classifier::factory()
        ->ofType(ClassifierTypeEnum::BENEFIT)
        ->count(2)
        ->create()
        ->pluck('value')
        ->all();

    $values = [
        'name' => fake()->company,
        'email' => fake()->companyEmail,
        'idNumber' => fake()->numerify('#########'),
        'website' => fake()->url,
        'culture' => fake()->text(500),
        'benefits' => $benefits
    ];

    $company = UpdateCompanyUseCase::make()->handle($user, $values);

    assertSame($values['name'], $company->name);
    assertSame($values['email'], $company->email);
    assertSame($values['idNumber'], $company->id_number);
    assertSame($values['website'], $company->website);
    assertSame($values['culture'], $company->culture);
    assertArraysAreSame($values['benefits'], $company->benefits->pluck('value')->all());
});
