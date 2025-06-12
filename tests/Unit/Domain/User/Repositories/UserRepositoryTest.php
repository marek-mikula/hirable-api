<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\User\Repositories;

use App\Enums\LanguageEnum;
use Domain\Company\Enums\RoleEnum;
use Domain\Company\Models\Company;
use Domain\User\Models\User;
use Domain\User\Repositories\Input\UserStoreInput;
use Domain\User\Repositories\Input\UserUpdateInput;
use Domain\User\Repositories\UserRepositoryInterface;

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertCollectionsAreSame;
use function Tests\Common\Helpers\assertDatetime;

/** @covers \Domain\User\Repositories\UserRepository::store */
it('tests store method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $company = Company::factory()->create();

    $input = new UserStoreInput(
        language: LanguageEnum::CS,
        firstname: fake()->firstName,
        lastname: fake()->lastName,
        email: fake()->email,
        password: fake()->password,
        agreementIp: fake()->ipv4,
        agreementAcceptedAt: now(),
        company: $company,
        companyRole: RoleEnum::ADMIN,
        companyOwner: fake()->boolean,
        phone: '+420776758768',
        prefix: 'Ing.',
        postfix: 'MBA',
        emailVerifiedAt: now()->subYear(),
    );

    $user = $repository->store($input);

    assertModelExists($user);
    assertSame($input->company->id, $user->company_id);
    assertSame($input->companyRole, $user->company_role);
    assertSame($input->companyOwner, $user->company_owner);
    assertSame($input->language, $user->language);
    assertSame($input->firstname, $user->firstname);
    assertSame($input->lastname, $user->lastname);
    assertSame($input->prefix, $user->prefix);
    assertSame($input->postfix, $user->postfix);
    assertSame($input->phone, $user->phone);
    assertSame($input->email, $user->email);
    assertSame($input->agreementIp, $user->agreement_ip);
    assertDatetime($input->agreementAcceptedAt, $user->agreement_accepted_at);
    assertDatetime($input->emailVerifiedAt, $user->email_verified_at);
    assertTrue($user->is_email_verified);
    assertTrue($user->relationLoaded('company'));
});

/** @covers \Domain\User\Repositories\UserRepository::update */
it('tests update method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $user = User::factory()
        ->ofLanguage(LanguageEnum::EN)
        ->create();

    $input = new UserUpdateInput(
        firstname: fake()->firstName,
        lastname: fake()->lastName,
        email: fake()->email,
        language: LanguageEnum::CS,
        prefix: 'Mgr.',
        postfix: 'MBA',
        phone: '+420776758768',
    );

    $user = $repository->update($user, $input);

    assertSame($input->language, $user->language);
    assertSame($input->firstname, $user->firstname);
    assertSame($input->lastname, $user->lastname);
    assertSame($input->email, $user->email);
    assertSame($input->prefix, $user->prefix);
    assertSame($input->postfix, $user->postfix);
    assertSame($input->phone, $user->phone);
});

/** @covers \Domain\User\Repositories\UserRepository::verifyEmail */
it('tests verifyEmail method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $timestamp = now()->subYear();

    $user = User::factory()->emailNotVerified()->create();

    $user = $repository->verifyEmail($user, timestamp: $timestamp);

    assertDatetime($user->email_verified_at, $timestamp);
});

/** @covers \Domain\User\Repositories\UserRepository::changePassword */
it('tests changePassword method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $user = User::factory()->create();

    $previousHash = $user->password;

    $user = $repository->changePassword($user, fake()->password);

    assertNotSame($user->password, $previousHash);
});

/** @covers \Domain\User\Repositories\UserRepository::findByEmail */
it('tests findByEmail method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $email = fake()->email;

    $user = User::factory()->ofEmail($email)->create();

    $foundUser = $repository->findByEmail($email);

    assertNotNull($foundUser);
    assertTrue($foundUser->is($user));
});

/** @covers \Domain\User\Repositories\UserRepository::getByIdsAndCompany */
it('tests getByIdsAndCompany method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $company1 = Company::factory()->create();
    $company2 = Company::factory()->create();

    $users = User::factory()->ofCompany($company1, RoleEnum::RECRUITER)->count(2)->create();

    // create dummy users in different company
    User::factory()->ofCompany($company2, RoleEnum::RECRUITER)->count(2)->create();

    $result1 = $repository->getByIdsAndCompany($company1, $users->pluck('id')->all());

    assertCollectionsAreSame($users, $result1);

    $result2 = $repository->getByIdsAndCompany($company2, $users->pluck('id')->all());

    assertEmpty($result2);
});
