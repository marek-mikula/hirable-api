<?php

declare(strict_types=1);

namespace Tests\Feature\Domain\Password\UseCases;

use App\Enums\ResponseCodeEnum;
use Domain\Password\Notifications\ResetRequestNotification;
use Domain\Password\UseCases\RequestPasswordResetUseCase;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function Pest\Laravel\assertDatabaseHas;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\Password\UseCases\RequestPasswordResetUseCase::handle */
it('tests requestReset method - not-existing email', function (): void {
    RequestPasswordResetUseCase::make()->handle('admin@example.com');

    Notification::assertNothingSent();
});

/** @covers \Domain\Password\UseCases\RequestPasswordResetUseCase::handle */
it('tests requestReset method - correct email', function (): void {
    $user = User::factory()->create();

    RequestPasswordResetUseCase::make()->handle($user->email);

    Notification::assertSentTo($user, ResetRequestNotification::class);

    assertDatabaseHas(Token::class, [
        'user_id' => $user->id,
        'type' => TokenTypeEnum::RESET_PASSWORD->value,
    ]);
});

/** @covers \Domain\Password\UseCases\RequestPasswordResetUseCase::handle */
it('tests requestReset method - existing token still active', function (): void {
    $throttleMinutes = 10;

    config()->set(sprintf('token.throttle.%s', TokenTypeEnum::RESET_PASSWORD->value), $throttleMinutes);

    $user = User::factory()->create();

    Token::factory()
        ->ofType(TokenTypeEnum::RESET_PASSWORD)
        ->ofUser($user)
        ->ofCreatedAt(now()->subMinutes($throttleMinutes - 1)) // -1 to be still valid
        ->create();

    assertHttpException(function () use ($user): void {
        RequestPasswordResetUseCase::make()->handle($user->email);
    }, ResponseCodeEnum::RESET_ALREADY_REQUESTED);

    Notification::assertNotSentTo($user, ResetRequestNotification::class);
});

/** @covers \Domain\Password\UseCases\RequestPasswordResetUseCase::handle */
it('tests requestReset method - existing token invalid', function (): void {
    $throttleMinutes = 10;

    config()->set(sprintf('token.throttle.%s', TokenTypeEnum::RESET_PASSWORD->value), $throttleMinutes);

    $user = User::factory()->create();

    Token::factory()
        ->ofType(TokenTypeEnum::RESET_PASSWORD)
        ->ofUser($user)
        ->ofCreatedAt(now()->subMinutes($throttleMinutes + 1)) // +1 to be invalid
        ->create();

    RequestPasswordResetUseCase::make()->handle($user->email);

    Notification::assertSentTo($user, ResetRequestNotification::class);

    assertDatabaseHas(Token::class, [
        'user_id' => $user->id,
        'type' => TokenTypeEnum::RESET_PASSWORD->value,
    ]);
});
