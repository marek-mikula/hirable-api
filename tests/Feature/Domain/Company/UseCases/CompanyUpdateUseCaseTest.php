<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Company\UseCases;

use Domain\Company\Models\Company;
use Domain\Company\UseCases\CompanyUpdateUseCase;

use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Company\UseCases\CompanyUpdateUseCase::handle */
it('correctly updates company - all attributes', function (): void {
    $company = Company::factory()
        ->create();

    $values = [
        'name' => fake()->company,
        'email' => fake()->companyEmail,
        'idNumber' => fake()->numerify('#########'),
        'website' => fake()->url,
    ];

    $company = CompanyUpdateUseCase::make()->handle($company, $values);

    assertSame($values['name'], $company->name);
    assertSame($values['email'], $company->email);
    assertSame($values['idNumber'], $company->id_number);
    assertSame($values['website'], $company->website);
});
