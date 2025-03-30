<?php

namespace Tests\Unit\Domain\Register\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use App\Models\Company;
use App\Models\Token;
use App\Models\User;
use Domain\Company\Enums\RoleEnum;
use Domain\Register\Http\Requests\Data\CompanyData;
use Domain\Register\Http\Requests\Data\RegisterData;
use Domain\Register\Notifications\RegisterRegisteredNotification;
use Domain\Register\UseCases\RegisterUseCase;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertDatetime;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\Register\UseCases\RegisterUseCase::handle */
it('tests token without email', function (): void {
    $token = Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofData([])
        ->create();

    $data = RegisterData::from([
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'password' => 'test123',
        'agreementIp' => '127.0.0.1',
        'agreementAcceptedAt' => now(),
        'company' => CompanyData::from([
            'name' => 'Alpacca s.r.o.',
            'email' => 'info@example.com',
            'idNumber' => '999000111',
            'website' => 'https://www.example.com',
        ]),
    ]);

    assertHttpException(function () use ($token, $data): void {
        RegisterUseCase::make()->handle($token, $data);
    }, ResponseCodeEnum::TOKEN_INVALID);
});

/** @covers \Domain\Register\UseCases\RegisterUseCase::handle */
it('tests successful registration', function (): void {
    $email = 'info@example.com';

    $registrationToken = Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofData(['email' => $email])
        ->create();

    $data = RegisterData::from([
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'password' => 'test123',
        'agreementIp' => '127.0.0.1',
        'agreementAcceptedAt' => now()->subDays(20),
        'company' => CompanyData::from([
            'name' => 'Alpacca s.r.o.',
            'email' => 'info@example.com',
            'idNumber' => '999000111',
            'website' => 'https://www.example.com',
        ]),
    ]);

    app()->setLocale(LanguageEnum::CS->value);

    $user = RegisterUseCase::make()->handle($registrationToken, $data);

    assertInstanceOf(User::class, $user);
    assertModelMissing($registrationToken);

    Notification::assertSentTo($user, RegisterRegisteredNotification::class);

    assertDatabaseHas(User::class, ['email' => $email]);
    assertDatabaseHas(Company::class, ['email' => $data->company->email]);

    /** @var Company $company */
    $company = Company::query()->whereEmail($data->company->email)->first();

    assertSame($data->company->name, $company->name);
    assertSame($data->company->email, $company->email);
    assertSame($data->company->idNumber, $company->id_number);
    assertSame($data->company->website, $company->website);

    /** @var User $user */
    $user = User::query()->whereEmail($email)->first();

    assertSame($company->id, $user->company_id);
    assertSame(RoleEnum::ADMIN, $user->company_role);
    assertSame($email, $user->email);
    assertSame($data->firstname, $user->firstname);
    assertSame($data->lastname, $user->lastname);
    assertSame($data->agreementIp, $user->agreement_ip);
    assertDatetime($data->agreementAcceptedAt, $user->agreement_accepted_at);
    assertSame(LanguageEnum::CS, $user->language);

    // users email address should be verified
    assertTrue($user->is_email_verified);
});
