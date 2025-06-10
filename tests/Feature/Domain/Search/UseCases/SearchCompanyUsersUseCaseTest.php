<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Search\UseCases;

use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\Search\Data\SearchData;
use Domain\Search\UseCases\SearchCompanyUsersUseCase;
use Domain\User\Models\User;

use function Tests\Common\Helpers\assertCollectionsAreSame;

/** @covers \Domain\Search\UseCases\SearchCompanyUsersUseCase::handle */
it('correctly searches company users - all', function (): void {
    $user = User::factory()->create();

    $otherCompany = Company::factory()->create();

    $users = User::factory()->ofCompany($user->company, RoleEnum::RECRUITER)->count(2)->create();

    // create another user with different company
    User::factory()->ofCompany($otherCompany, RoleEnum::RECRUITER)->create();

    $searchData = SearchData::from([
        'query' => null,
        'limit' => 100
    ]);

    // ignore auth user
    $result = SearchCompanyUsersUseCase::make()->handle(user: $user, data: $searchData, ignoreAuth: true);

    assertCollectionsAreSame($users, $result->pluck('value'));

    // do not ignore auth user
    $result = SearchCompanyUsersUseCase::make()->handle(user: $user, data: $searchData, ignoreAuth: false);

    assertCollectionsAreSame($users->add($user), $result->pluck('value'));
});

/** @covers \Domain\Search\UseCases\SearchCompanyUsersUseCase::handle */
it('correctly searches company users - with query', function (): void {
    $word = fake()->word();

    $user = User::factory()->create([
        'firstname' => sprintf('%s.%s', fake()->firstName(), $word),
    ]);

    $otherCompany = Company::factory()->create();

    $user1 = User::factory()->ofCompany($user->company, RoleEnum::RECRUITER)->create([
        'firstname' => sprintf('%s.%s', fake()->firstName(), $word),
    ]);
    $user2 = User::factory()->ofCompany($user->company, RoleEnum::RECRUITER)->create([
        'lastname' => sprintf('%s.%s', $word, fake()->lastName()),
    ]);
    $user3 = User::factory()->ofCompany($user->company, RoleEnum::RECRUITER)->create([
        'email' => sprintf('%s.%s.%s', $word, fake()->safeEmail(), $word),
    ]);

    // create another user with different company
    User::factory()->ofCompany($otherCompany, RoleEnum::RECRUITER)->create([
        'firstname' => sprintf('%s.%s', $word, fake()->firstName())
    ]);

    $data = SearchData::from([
        'query' => $word,
        'limit' => 100
    ]);

    $result = SearchCompanyUsersUseCase::make()->handle(user: $user, data: $data, ignoreAuth: true);

    assertCollectionsAreSame(
        collect([
            $user1->id,
            $user2->id,
            $user3->id,
        ]),
        $result->pluck('value')
    );

    $result = SearchCompanyUsersUseCase::make()->handle(user: $user, data: $data, ignoreAuth: false);

    assertCollectionsAreSame(
        collect([
            $user->id,
            $user1->id,
            $user2->id,
            $user3->id,
        ]),
        $result->pluck('value')
    );
});
