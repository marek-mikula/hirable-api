<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Search\UseCases;

use Domain\Company\Models\Company;
use Domain\Company\Models\CompanyContact;
use Domain\Search\Data\SearchData;
use Domain\Search\UseCases\SearchCompanyContactsUseCase;
use Domain\User\Models\User;

use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Search\UseCases\SearchCompanyContactsUseCase::handle */
it('correctly searches company contacts - all', function (): void {
    $user = User::factory()->create();

    $otherCompany = Company::factory()->create();

    $contacts = CompanyContact::factory()->ofCompany($user->company)->count(2)->create();

    // create another company contact with different company
    CompanyContact::factory()->ofCompany($otherCompany)->create();

    $result = SearchCompanyContactsUseCase::make()->handle(
        $user,
        SearchData::from([
            'query' => null,
            'limit' => 100
        ])
    );

    assertCollectionsAreSame(
        $contacts,
        $result->pluck('value')
    );
});

/** @covers \Domain\Search\UseCases\SearchCompanyContactsUseCase::handle */
it('correctly searches company contacts - with query', function (): void {
    $user = User::factory()->create();

    $otherCompany = Company::factory()->create();

    $word = fake()->word();

    $contact1 = CompanyContact::factory()->ofCompany($user->company)->create([
        'firstname' => sprintf('%s.%s', fake()->firstName(), $word),
    ]);
    $contact2 = CompanyContact::factory()->ofCompany($user->company)->create([
        'lastname' => sprintf('%s.%s', $word, fake()->lastName()),
    ]);
    $contact3 = CompanyContact::factory()->ofCompany($user->company)->create([
        'email' => sprintf('%s.%s.%s', $word, fake()->safeEmail(), $word),
    ]);
    $contact4 = CompanyContact::factory()->ofCompany($user->company)->create([
        'company_name' => sprintf('%s.%s', fake()->company(), $word),
    ]);

    // create another company contact with different company
    CompanyContact::factory()->ofCompany($otherCompany)->create([
        'company_name' => sprintf('%s.%s', fake()->company(), $word)
    ]);

    $result = SearchCompanyContactsUseCase::make()->handle(
        $user,
        SearchData::from([
            'query' => $word,
            'limit' => 100
        ])
    );

    assertCollectionsAreSame(
        collect([
            $contact1->id,
            $contact2->id,
            $contact3->id,
            $contact4->id,
        ]),
        $result->pluck('value')
    );
});
