<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Password\UseCases;

use App\Models\Token;
use App\Models\User;
use Domain\Password\Notifications\ChangedNotification;
use Domain\Password\UseCases\ResetPasswordUseCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

/** @covers \Domain\Password\UseCases\ResetPasswordUseCase::handle */
it('tests password reset', function (): void {
    $password = 'test1234';

    $user = User::factory()
        ->ofPassword('test123')
        ->create();

    $token = Token::factory()
        ->ofType(TokenTypeEnum::RESET_PASSWORD)
        ->ofUser($user)
        ->create();

    ResetPasswordUseCase::make()->handle($token, $password);

    $token->refresh();

    assertNotNull($token->used_at);

    $user->refresh();

    assertTrue(Hash::check($password, $user->password));

    Notification::assertSentTo($user, ChangedNotification::class);
});
