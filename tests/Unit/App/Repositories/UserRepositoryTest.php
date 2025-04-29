<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repositories;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Models\Company;
use App\Models\User;
use App\Repositories\User\Input\StoreInput;
use App\Repositories\User\Input\UpdateInput;
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

    $input = StoreInput::from([
        'language' => LanguageEnum::CS,
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'email' => 'thomas.example@example.com',
        'password' => 'test',
        'agreementIp' => '127.0.0.1',
        'agreementAcceptedAt' => now(),
        'emailVerifiedAt' => now()->subYear(),
        'company' => $company,
        'companyRole' => RoleEnum::ADMIN,
        'prefix' => 'Ing.',
        'postfix' => 'MBA',
        'phone' => '+420776758768',
    ]);

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
    assertSame("{$input->prefix} {$input->firstname} {$input->lastname}, {$input->postfix}", $user->full_name);
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
        ->ofTechnicalNotifications(false, false)
        ->ofMarketingNotifications(false, false)
        ->ofApplicationNotifications(false, false)
        ->ofLanguage(LanguageEnum::EN)
        ->ofTimezone(TimezoneEnum::AFRICA_ABIDJAN)
        ->create();

    $input = UpdateInput::from([
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'email' => 'thomas.example@example.com',
        'timezone' => TimezoneEnum::EUROPE_PRAGUE,
        'notificationTechnicalMail' => true,
        'notificationTechnicalApp' => true,
        'notificationMarketingMail' => true,
        'notificationMarketingApp' => true,
        'notificationApplicationMail' => true,
        'notificationApplicationApp' => true,
        'language' => LanguageEnum::CS,
        'prefix' => 'Mgr.',
        'postfix' => 'MBA',
        'phone' => '+420776758768',
    ]);

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

    $user = $repository->changePassword($user, 'Password123');

    assertNotSame($user->password, $previousHash);
});

/** @covers \App\Repositories\User\UserRepository::findByEmail */
it('tests findByEmail method', function (): void {
    /** @var UserRepositoryInterface $repository */
    $repository = app(UserRepositoryInterface::class);

    $email = 'example@example.com';

    $user = User::factory()->create(['email' => $email]);

    $foundUser = $repository->findByEmail($email);

    assertNotNull($foundUser);
    assertTrue($foundUser->is($user));
});
