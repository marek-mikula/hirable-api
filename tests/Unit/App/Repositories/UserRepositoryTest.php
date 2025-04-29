<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repositories;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Models\Company;
use App\Models\User;
use App\Repositories\User\Input\UserStoreInput;
use App\Repositories\User\Input\UserUpdateInput;
use App\Repositories\User\UserRepositoryInterface;
use Domain\Company\Enums\RoleEnum;

use function Pest\Laravel\assertModelExists;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertDatetime;

/** @covers \App\Repositories\User\UserRepository::store */
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
        phone: '+420776758768',
        prefix: 'Ing.',
        postfix: 'MBA',
        emailVerifiedAt: now()->subYear(),
    );

    $user = $repository->store($input);

    assertModelExists($user);
    assertSame($input->company->id, $user->company_id);
    assertSame($input->companyRole, $user->company_role);
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

/** @covers \App\Repositories\User\UserRepository::update */
it('tests update method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $user = User::factory()
        ->ofTechnicalNotifications(mail: fake()->boolean, app: fake()->boolean)
        ->ofMarketingNotifications(mail: fake()->boolean, app: fake()->boolean)
        ->ofApplicationNotifications(mail: fake()->boolean, app: fake()->boolean)
        ->ofLanguage(LanguageEnum::EN)
        ->ofTimezone(TimezoneEnum::AFRICA_ABIDJAN)
        ->create();

    $input = new UserUpdateInput(
        firstname: fake()->firstName,
        lastname: fake()->lastName,
        email: fake()->email,
        timezone: TimezoneEnum::EUROPE_PRAGUE,
        notificationTechnicalMail: fake()->boolean,
        notificationTechnicalApp: fake()->boolean,
        notificationMarketingMail: fake()->boolean,
        notificationMarketingApp: fake()->boolean,
        notificationApplicationMail: fake()->boolean,
        notificationApplicationApp: fake()->boolean,
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
    assertSame($input->timezone, $user->timezone);
    assertSame($input->prefix, $user->prefix);
    assertSame($input->postfix, $user->postfix);
    assertSame($input->phone, $user->phone);
    assertSame($input->notificationTechnicalMail, $user->notification_technical_mail);
    assertSame($input->notificationTechnicalApp, $user->notification_technical_app);
    assertSame($input->notificationMarketingMail, $user->notification_marketing_mail);
    assertSame($input->notificationMarketingApp, $user->notification_marketing_app);
    assertSame($input->notificationApplicationMail, $user->notification_application_mail);
    assertSame($input->notificationApplicationApp, $user->notification_application_app);
});

/** @covers \App\Repositories\User\UserRepository::verifyEmail */
it('tests verifyEmail method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $timestamp = now()->subYear();

    $user = User::factory()->emailNotVerified()->create();

    $user = $repository->verifyEmail($user, timestamp: $timestamp);

    assertDatetime($user->email_verified_at, $timestamp);
});

/** @covers \App\Repositories\User\UserRepository::changePassword */
it('tests changePassword method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $user = User::factory()->create();

    $previousHash = $user->password;

    $user = $repository->changePassword($user, fake()->password);

    assertNotSame($user->password, $previousHash);
});

/** @covers \App\Repositories\User\UserRepository::findByEmail */
it('tests findByEmail method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $email = fake()->email;

    $user = User::factory()->ofEmail($email)->create();

    $foundUser = $repository->findByEmail($email);

    assertNotNull($foundUser);
    assertTrue($foundUser->is($user));
});
