<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repositories;

use App\Models\Company;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Company\Input\CompanyStoreInput;
use App\Repositories\Company\Input\CompanyUpdateInput;

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \App\Repositories\Company\CompanyRepository::find */
it('tests find method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $company = Company::factory()->create();

    // create couple more companies
    Company::factory()->count(5)->create();

    assertTrue($company->is($repository->find($company->id)));
});

/** @covers \App\Repositories\Company\CompanyRepository::store */
it('tests store method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $input = new CompanyStoreInput(
        name: fake()->company,
        email: fake()->companyEmail,
        idNumber: fake()->numerify('#########'),
        website: fake()->url,
    );

    $company = $repository->store($input);

    assertModelExists($company);
    assertSame($input->name, $company->name);
    assertSame($input->email, $company->email);
    assertSame($input->idNumber, $company->id_number);
    assertSame($input->website, $company->website);
});

/** @covers \App\Repositories\Company\CompanyRepository::update */
it('tests update method', function (): void {
    /** @var CompanyRepositoryInterface $repository */
    $repository = app(CompanyRepositoryInterface::class);

    $input = new CompanyUpdateInput(
        name: fake()->company,
        email: fake()->companyEmail,
        idNumber: fake()->numerify('#########'),
        website: fake()->url,
    );

    $company = $repository->update(Company::factory()->create(), $input);

    assertSame($input->name, $company->name);
    assertSame($input->email, $company->email);
    assertSame($input->idNumber, $company->id_number);
    assertSame($input->website, $company->website);
});
