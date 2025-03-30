<?php

namespace Tests\Unit\App\Repositories;

use App\Models\Company;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Repositories\Company\Input\StoreInput;
use App\Repositories\Company\Input\UpdateInput;

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

    $input = StoreInput::from([
        'name' => 'Alpacca s.r.o.',
        'email' => 'info@example.com',
        'idNumber' => '999000111',
        'website' => 'https://www.example.com',
    ]);

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

    $input = UpdateInput::from([
        'name' => 'Alpacca s.r.o.',
        'email' => 'info@example.com',
        'idNumber' => '999000111',
        'website' => 'https://www.example.com',
    ]);

    $company = $repository->update(Company::factory()->create(), $input);

    assertSame($input->name, $company->name);
    assertSame($input->email, $company->email);
    assertSame($input->idNumber, $company->id_number);
    assertSame($input->website, $company->website);
});
