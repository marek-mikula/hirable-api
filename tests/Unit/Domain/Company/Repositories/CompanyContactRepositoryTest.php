<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Company\Repositories;

use App\Enums\LanguageEnum;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Company\Repositories\CompanyContactRepository::store */
it('tests store method', function (): void {
    /** @var CompanyContactRepositoryInterface $repository */
    $repository = app(CompanyContactRepositoryInterface::class);

    $company = Company::factory()->create();

    $input = new CompanyContactStoreInput(
        company: $company,
        language: fake()->randomElement(LanguageEnum::cases()),
        firstname: fake()->firstName,
        lastname: fake()->lastName,
        email: fake()->safeEmail,
        note: fake()->text(300),
        companyName: fake()->company,
    );

    $contact = $repository->store($input);

    assertSame($input->company->id, $contact->company_id);
    assertSame($input->language, $contact->language);
    assertSame($input->firstname, $contact->firstname);
    assertSame($input->lastname, $contact->lastname);
    assertSame($input->email, $contact->email);
    assertSame($input->note, $contact->note);
    assertSame($input->companyName, $contact->company_name);
    assertTrue($contact->relationLoaded('company'));
});

/** @covers \Domain\Company\Repositories\CompanyContactRepository::getByIdsAndCompany */
it('tests getByIdsAndCompany method', function (): void {
    /** @var CompanyContactRepositoryInterface $repository */
    $repository = app(CompanyContactRepositoryInterface::class);

    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $contacts = CompanyContact::factory()->count(2)->ofCompany($company1)->create();

    // create other dummy contacts
    CompanyContact::factory()->count(2)->ofCompany($company2)->create();

    $result1 = $repository->getByIdsAndCompany($company1, $contacts->pluck('id')->all());

    assertCollectionsAreSame($contacts, $result1);

    $result2 = $repository->getByIdsAndCompany($company2, $contacts->pluck('id')->all());

    assertEmpty($result2);
});
