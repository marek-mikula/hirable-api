<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Company\Repositories;

use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactSuggestRepositoryInterface;

use function Tests\Common\Helpers\assertArraysAreSame;

/** @covers \Domain\Company\Repositories\CompanyContactSuggestRepository::suggestCompanies */
it('tests suggestCompanies method', function (): void {
    /** @var CompanyContactSuggestRepositoryInterface $repository */
    $repository = app(CompanyContactSuggestRepositoryInterface::class);

    $company = Company::factory()->create();

    $contacts = CompanyContact::factory()->ofCompany($company)->count(5)->create();

    $result = $repository->suggestCompanies($company, value: null);

    assertArraysAreSame($contacts->pluck('company_name')->all(), $result);
});

/** @covers \Domain\Company\Repositories\CompanyContactSuggestRepository::suggestCompanies */
it('tests suggestCompanies method with query', function (): void {
    /** @var CompanyContactSuggestRepositoryInterface $repository */
    $repository = app(CompanyContactSuggestRepositoryInterface::class);

    $company = Company::factory()->create();

    $query = fake()->company;

    $word = fake()->word;

    $contact1 = CompanyContact::factory()->ofCompany($company)->create(['company_name' => "{$query}{$word}"]);
    $contact2 = CompanyContact::factory()->ofCompany($company)->create(['company_name' => "{$word}{$query}{$word}"]);
    $contact3 = CompanyContact::factory()->ofCompany($company)->create(['company_name' => "{$word}{$query}"]);

    $result = $repository->suggestCompanies($company, value: $query);

    assertArraysAreSame([
        $contact1->company_name,
        $contact2->company_name,
        $contact3->company_name,
    ], $result);
});
