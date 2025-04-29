<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Auth\Http\Requests;

use App\Enums\LanguageEnum;
use App\Enums\TimezoneEnum;
use App\Models\User;
use Domain\Auth\Http\Requests\AuthUpdateRequest;
use Illuminate\Support\Str;
use Tests\Common\Data\ValidationData;

use function Tests\Common\Helpers\actingAs;
use function Tests\Common\Helpers\assertRequestValid;

/** @covers \Domain\Auth\Http\Requests\AuthUpdateRequest::rules */
it('tests request validation rules', function (ValidationData $data): void {
    $user = User::factory()
        ->ofEmail('test@example.com')
        ->ofPassword('SecretPassword.123')
        ->create();

    // set currently logged-in user because current
    // password validation
    actingAs($user, 'api');

    assertRequestValid(class: AuthUpdateRequest::class, data: $data);
})->with([
    [new ValidationData(data: ['keys' => null], invalidInputs: ['keys'])],
    [new ValidationData(data: ['keys' => []], invalidInputs: ['keys'])],
    [new ValidationData(data: ['keys' => [null, 'test']], invalidInputs: ['keys.0', 'keys.1'])],
    [new ValidationData(data: ['keys' => ['firstname'], 'firstname' => null], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['keys' => ['firstname'], 'firstname' => 1], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['keys' => ['firstname'], 'firstname' => Str::repeat('a', 256)], invalidInputs: ['firstname'])],
    [new ValidationData(data: ['keys' => ['lastname'], 'lastname' => null], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['keys' => ['lastname'], 'lastname' => 1], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['keys' => ['lastname'], 'lastname' => Str::repeat('a', 256)], invalidInputs: ['lastname'])],
    [new ValidationData(data: ['keys' => ['email'], 'email' => null], invalidInputs: ['email'])],
    [new ValidationData(data: ['keys' => ['email'], 'email' => 1], invalidInputs: ['email'])],
    [new ValidationData(data: ['keys' => ['email'], 'email' => 'test'], invalidInputs: ['email'])],
    [new ValidationData(data: ['keys' => ['email'], 'email' => 'test'.Str::repeat('a', 256).'@example.com'], invalidInputs: ['email'])],
    [new ValidationData(data: ['keys' => ['email'], 'email' => 'test2@example.com'], invalidInputs: ['email'], before: function (): void {
        User::factory()->ofEmail('test2@example.com')->create();
    })],
    [new ValidationData(data: ['keys' => ['password'], 'password' => null], invalidInputs: ['password'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 1], invalidInputs: ['password'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'Password'], invalidInputs: ['password'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'Password123'], invalidInputs: ['password'])],
    [new ValidationData(data: ['keys' => ['password'], 'oldPassword' => null], invalidInputs: ['oldPassword'])],
    [new ValidationData(data: ['keys' => ['password'], 'oldPassword' => 1], invalidInputs: ['oldPassword'])],
    [new ValidationData(data: ['keys' => ['password'], 'oldPassword' => 'test'], invalidInputs: ['oldPassword'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'Password.123', 'passwordConfirm' => null], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'Password.123', 'passwordConfirm' => 1], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['keys' => ['password'], 'password' => 'Password.123', 'passwordConfirm' => 'test'], invalidInputs: ['passwordConfirm'])],
    [new ValidationData(data: ['keys' => ['timezone'], 'timezone' => 1], invalidInputs: ['timezone'])],
    [new ValidationData(data: ['keys' => ['timezone'], 'timezone' => 'test'], invalidInputs: ['timezone'])],
    [new ValidationData(data: ['keys' => ['notificationTechnicalMail'], 'notificationTechnicalMail' => null], invalidInputs: ['notificationTechnicalMail'])],
    [new ValidationData(data: ['keys' => ['notificationTechnicalMail'], 'notificationTechnicalMail' => 'test'], invalidInputs: ['notificationTechnicalMail'])],
    [new ValidationData(data: ['keys' => ['notificationTechnicalApp'], 'notificationTechnicalApp' => null], invalidInputs: ['notificationTechnicalApp'])],
    [new ValidationData(data: ['keys' => ['notificationTechnicalApp'], 'notificationTechnicalApp' => 'test'], invalidInputs: ['notificationTechnicalApp'])],
    [new ValidationData(data: ['keys' => ['notificationMarketingMail'], 'notificationMarketingMail' => null], invalidInputs: ['notificationMarketingMail'])],
    [new ValidationData(data: ['keys' => ['notificationMarketingMail'], 'notificationMarketingMail' => 'test'], invalidInputs: ['notificationMarketingMail'])],
    [new ValidationData(data: ['keys' => ['notificationMarketingApp'], 'notificationMarketingApp' => null], invalidInputs: ['notificationMarketingApp'])],
    [new ValidationData(data: ['keys' => ['notificationMarketingApp'], 'notificationMarketingApp' => 'test'], invalidInputs: ['notificationMarketingApp'])],
    [new ValidationData(data: ['keys' => ['notificationApplicationMail'], 'notificationApplicationMail' => null], invalidInputs: ['notificationApplicationMail'])],
    [new ValidationData(data: ['keys' => ['notificationApplicationMail'], 'notificationApplicationMail' => 'test'], invalidInputs: ['notificationApplicationMail'])],
    [new ValidationData(data: ['keys' => ['notificationApplicationApp'], 'notificationApplicationApp' => null], invalidInputs: ['notificationApplicationApp'])],
    [new ValidationData(data: ['keys' => ['notificationApplicationApp'], 'notificationApplicationApp' => 'test'], invalidInputs: ['notificationApplicationApp'])],
    [new ValidationData(data: ['keys' => ['language'], 'language' => null], invalidInputs: ['language'])],
    [new ValidationData(data: ['keys' => ['language'], 'language' => 1], invalidInputs: ['language'])],
    [new ValidationData(data: ['keys' => ['prefix'], 'prefix' => 1], invalidInputs: ['prefix'])],
    [new ValidationData(data: ['keys' => ['prefix'], 'prefix' => Str::repeat('a', 11)], invalidInputs: ['prefix'])],
    [new ValidationData(data: ['keys' => ['postfix'], 'postfix' => 1], invalidInputs: ['postfix'])],
    [new ValidationData(data: ['keys' => ['postfix'], 'postfix' => Str::repeat('a', 11)], invalidInputs: ['postfix'])],
    [new ValidationData(data: ['keys' => ['phone'], 'phone' => 1], invalidInputs: ['phone'])],
    [new ValidationData(data: ['keys' => ['phone'], 'phone' => Str::repeat('a', 21)], invalidInputs: ['phone'])],
    [new ValidationData(data: [
        'keys' => [
            'firstname',
            'lastname',
            'email',
            'timezone',
            'password',
            'notificationTechnicalMail',
            'notificationTechnicalApp',
            'notificationTechnicalPush',
            'notificationMarketingMail',
            'notificationMarketingApp',
            'notificationMarketingPush',
            'notificationApplicationMail',
            'notificationApplicationApp',
            'notificationApplicationPush',
            'language',
            'prefix',
            'postfix',
            'phone',
        ],
        'firstname' => 'Thomas',
        'lastname' => 'Cook',
        'email' => 'thomas-cook@example.com',
        'password' => 'Password.123',
        'oldPassword' => 'SecretPassword.123',
        'passwordConfirm' => 'Password.123',
        'timezone' => TimezoneEnum::AFRICA_ABIDJAN->value,
        'notificationTechnicalMail' => 1,
        'notificationTechnicalApp' => 1,
        'notificationMarketingMail' => 1,
        'notificationMarketingApp' => 1,
        'notificationApplicationMail' => 1,
        'notificationApplicationApp' => 1,
        'language' => LanguageEnum::CS->value,
        'prefix' => 'Ing',
        'postfix' => 'MBA',
        'phone' => '+420665768955',
    ], invalidInputs: [])],
]);
