<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Company\Repositories;

use App\Enums\LanguageEnum;
use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Company\Repositories\CompanyContactRepositoryInterface;
use Domain\Company\Repositories\CompanyContactSuggestRepositoryInterface;
use Domain\Company\Repositories\Input\CompanyContactStoreInput;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertArraysAreSame;
use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Company\Repositories\CompanyContactSuggestRepository::suggestCompanies */
it('tests suggestCompanies method', function (): void {
    /** @var CompanyContactSuggestRepositoryInterface $repository */
    $repository = app(CompanyContactSuggestRepositoryInterface::class);

    $company = Company::factory()->create();

    $contacts = CompanyContact::factory()->ofCompany($company)->count(5)->create();

    $result = $repository->suggestCompanies($company, value: null);

    assertArraysAreSame($contacts->pluck('company_name')->all(), $result);
});
