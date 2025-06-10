<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Auth\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use Domain\Auth\UseCases\AuthUpdateUseCase;
use Domain\Password\Notifications\ChangedNotification;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Notification;

use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\Auth\UseCases\AuthUpdateUseCase::handle */
it('tests update user use case - all attributes', function (): void {
    $user = User::factory()
        ->ofLanguage(LanguageEnum::EN)
        ->create();

    $data = [
        'firstname' => 'Thomas',
        'lastname' => 'Example',
        'email' => 'example@example.com',
        'language' => LanguageEnum::CS,
        'prefix' => 'Ing.',
        'postfix' => 'MBA',
        'phone' => '+420887656453',
    ];

    $user = AuthUpdateUseCase::make()->handle($user, $data);

    assertSame($data['firstname'], $user->firstname);
    assertSame($data['lastname'], $user->lastname);
    assertSame($data['prefix'], $user->prefix);
    assertSame($data['postfix'], $user->postfix);
    assertSame($data['phone'], $user->phone);
    assertSame($data['email'], $user->email);
    assertSame($data['language'], $user->language);
});

/** @covers \Domain\Auth\UseCases\AuthUpdateUseCase::handle */
it('tests update user use case - only password', function (): void {
    $user = User::factory()->ofPassword('Password.1234')->create();

    $data = [
        'password' => 'Password.123',
    ];

    $passwordPrevious = $user->password;

    $user = AuthUpdateUseCase::make()->handle($user, $data);

    assertNotSame($passwordPrevious, $user->password);

    Notification::assertSentTo($user, ChangedNotification::class);
});

/** @covers \Domain\Auth\UseCases\AuthUpdateUseCase::handle */
it('tests update user use case - same password must throw an error', function (): void {
    $user = User::factory()->ofPassword('Password.123')->create();

    $data = [
        'password' => 'Password.123',
    ];

    assertHttpException(function () use ($user, $data): void {
        AuthUpdateUseCase::make()->handle($user, $data);
    }, ResponseCodeEnum::CLIENT_ERROR);

    Notification::assertNotSentTo($user, ChangedNotification::class);
});
