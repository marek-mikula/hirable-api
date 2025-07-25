<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Company\Repositories;

use App\Enums\LanguageEnum;
use Domain\Company\Models\Company;
use Domain\Company\Repositories\CompanyRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyStoreInput;
use Domain\Company\Repositories\Input\CompanyUpdateInput;

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \Domain\Company\Repositories\CompanyRepository::find */
it('tests find method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $company = Company::factory()->create();

    // create couple more companies
    Company::factory()->count(5)->create();

    assertTrue($company->is($repository->find($company->id)));
});

/** @covers \Domain\Company\Repositories\CompanyRepository::store */
it('tests store method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $input = new CompanyStoreInput(
        name: fake()->company,
        email: fake()->companyEmail,
        idNumber: fake()->numerify('#########'),
        website: fake()->url,
        aiOutputLanguage: fake()->randomElement(LanguageEnum::cases()),
    );

    $company = $repository->store($input);

    assertModelExists($company);
    assertSame($input->aiOutputLanguage, $company->ai_output_language);
    assertSame($input->name, $company->name);
    assertSame($input->email, $company->email);
    assertSame($input->idNumber, $company->id_number);
    assertSame($input->website, $company->website);
});

/** @covers \Domain\Company\Repositories\CompanyRepository::update */
it('tests update method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $input = new CompanyUpdateInput(
        name: fake()->company,
        email: fake()->companyEmail,
        idNumber: fake()->numerify('#########'),
        website: fake()->url,
        aiOutputLanguage: fake()->randomElement(LanguageEnum::cases()),
    );

    $company = $repository->update(Company::factory()->create(), $input);

    assertSame($input->name, $company->name);
    assertSame($input->email, $company->email);
    assertSame($input->idNumber, $company->id_number);
    assertSame($input->website, $company->website);
    assertSame($input->aiOutputLanguage, $company->ai_output_language);
});
