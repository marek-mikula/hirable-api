<?php

namespace Tests\Feature\Domain\Company\UseCases;

use App\Models\Company;
use App\Models\User;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\UseCases\UpdateCompanyUseCase;

use function PHPUnit\Framework\assertSame;

/** @covers \Domain\Company\UseCases\UpdateCompanyUseCase::handle */
it('tests update company use case - all attributes', function (): void {
    $company = Company::factory()
        ->create();

    $user = User::factory()
        ->ofCompany($company, RoleEnum::ADMIN)
        ->create();

    $values = [
        'name' => fake()->company,
        'email' => fake()->companyEmail,
        'idNumber' => fake()->numerify('#########'),
        'website' => fake()->url,
    ];

    $company = UpdateCompanyUseCase::make()->handle($user, $values);

    assertSame($values['name'], $company->name);
    assertSame($values['email'], $company->email);
    assertSame($values['idNumber'], $company->id_number);
    assertSame($values['website'], $company->website);
});
