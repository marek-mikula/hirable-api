<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Register\UseCases;

use App\Enums\ResponseCodeEnum;
use Domain\Register\Notifications\RegisterRequestNotification;
use Domain\Register\UseCases\RequestRegistrationUseCase;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Support\Facades\Notification;
use Mockery\MockInterface;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\mock;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\Register\UseCases\RequestRegistrationUseCase::handle */
it('tests invalid email', function (): void {
    assertDatabaseEmpty(Token::class);

    mock(Dispatcher::class, function (MockInterface $mock): void {
        $mock
            ->shouldReceive('sendNow')
            ->once()
            ->andThrow(new \Exception('Cannot reach email.'));
    });

    assertHttpException(function (): void {
        RequestRegistrationUseCase::make()->handle('wrong_email');
    }, ResponseCodeEnum::CLIENT_ERROR);

    // assert token was deleted if email
    // sending fails
    assertDatabaseEmpty(Token::class);
});

/** @covers \Domain\Register\UseCases\RequestRegistrationUseCase::handle */
it('tests correct email', function (): void {
    $email = 'test@example.com';

    assertDatabaseEmpty(Token::class);

    RequestRegistrationUseCase::make()->handle($email);

    Notification::assertSentOnDemand(RegisterRequestNotification::class);

    assertDatabaseHas(Token::class, [
        'type' => TokenTypeEnum::REGISTRATION->value,
        'data->email' => $email,
    ]);
});

/** @covers \Domain\Register\UseCases\RequestRegistrationUseCase::handle */
it('tests existing token still active', function (): void {
    $throttleMinutes = 10;

    config()->set(sprintf('token.throttle.%s', TokenTypeEnum::REGISTRATION->value), $throttleMinutes);

    $email = 'test@example.com';

    Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofData(['email' => $email])
        ->ofCreatedAt(now()->subMinutes($throttleMinutes - 1)) // -1 to be still valid
        ->create();

    assertDatabaseCount(Token::class, 1);

    assertHttpException(function () use ($email): void {
        RequestRegistrationUseCase::make()->handle($email);
    }, ResponseCodeEnum::REGISTRATION_ALREADY_REQUESTED);

    Notification::assertNothingSent();

    assertDatabaseCount(Token::class, 1);
});

/** @covers \Domain\Register\UseCases\RequestRegistrationUseCase::handle */
it('tests existing token invalid', function (): void {
    $throttleMinutes = 10;

    config()->set(sprintf('token.throttle.%s', TokenTypeEnum::REGISTRATION->value), $throttleMinutes);

    $email = 'test@example.com';

    Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofData(['email' => $email])
        ->ofCreatedAt(now()->subMinutes($throttleMinutes + 1)) // +1 to be invalid
        ->create();

    assertDatabaseCount(Token::class, 1);

    RequestRegistrationUseCase::make()->handle($email);

    Notification::assertSentOnDemand(RegisterRequestNotification::class);

    assertDatabaseCount(Token::class, 2);
});
