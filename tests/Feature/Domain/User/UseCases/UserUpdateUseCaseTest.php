<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\User\UseCases;

use App\Enums\LanguageEnum;
use App\Enums\ResponseCodeEnum;
use Domain\Password\Events\PasswordChanged;
use Domain\Password\Notifications\PasswordChangedNotification;
use Domain\User\Models\User;
use Domain\User\UseCases\UserUpdateUseCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

use function PHPUnit\Framework\assertNotSame;
use function PHPUnit\Framework\assertSame;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\User\UseCases\UserUpdateUseCase::handle */
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

    $user = UserUpdateUseCase::make()->handle($user, $data);

    assertSame($data['firstname'], $user->firstname);
    assertSame($data['lastname'], $user->lastname);
    assertSame($data['prefix'], $user->prefix);
    assertSame($data['postfix'], $user->postfix);
    assertSame($data['phone'], $user->phone);
    assertSame($data['email'], $user->email);
    assertSame($data['language'], $user->language);
});

/** @covers \Domain\User\UseCases\UserUpdateUseCase::handle */
it('tests update user use case - only password', function (): void {
    Event::fake([
        PasswordChanged::class,
    ]);

    $user = User::factory()->ofPassword('Password.1234')->create();

    $data = [
        'password' => 'Password.123',
    ];

    $passwordPrevious = $user->password;

    $user = UserUpdateUseCase::make()->handle($user, $data);

    assertNotSame($passwordPrevious, $user->password);

    Event::assertDispatched(PasswordChanged::class);
});

/** @covers \Domain\User\UseCases\UserUpdateUseCase::handle */
it('tests update user use case - same password must throw an error', function (): void {
    $user = User::factory()->ofPassword('Password.123')->create();

    $data = [
        'password' => 'Password.123',
    ];

    assertHttpException(function () use ($user, $data): void {
        UserUpdateUseCase::make()->handle($user, $data);
    }, ResponseCodeEnum::CLIENT_ERROR);

    Notification::assertNotSentTo($user, PasswordChangedNotification::class);
});
