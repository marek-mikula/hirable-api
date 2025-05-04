<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Verification\UseCases;

use App\Enums\ResponseCodeEnum;
use App\Models\User;
use Domain\Verification\Notifications\EmailVerifiedNotification;
use Domain\Verification\UseCases\VerifyEmailUseCase;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertHttpException;

/** @covers \Domain\Verification\UseCases\VerifyEmailUseCase::handle */
it('tests verify method - missing user', function (): void {
    $token = Token::factory()
        ->ofType(TokenTypeEnum::EMAIL_VERIFICATION)
        ->create();

    assertHttpException(function () use ($token): void {
        VerifyEmailUseCase::make()->handle($token);
    }, ResponseCodeEnum::TOKEN_INVALID);
});

/** @covers \Domain\Verification\UseCases\VerifyEmailUseCase::handle */
it('tests verify method - correct token', function (): void {
    $user = User::factory()->emailNotVerified()->create();

    $token = Token::factory()
        ->ofType(TokenTypeEnum::EMAIL_VERIFICATION)
        ->ofUser($user)
        ->create();

    assertNull($user->email_verified_at);
    assertFalse($user->is_email_verified);

    $user = VerifyEmailUseCase::make()->handle($token);

    $token->refresh();

    assertNotNull($user->email_verified_at);
    assertTrue($user->is_email_verified);

    assertNotNull($token->used_at);

    Notification::assertSentTo($user, EmailVerifiedNotification::class);
});
