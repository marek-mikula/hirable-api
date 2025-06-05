<?php

declare(strict_types=1);

namespace Tests\Process;

use App\Enums\ResponseCodeEnum;
use Domain\Password\Notifications\ChangedNotification;
use Domain\Password\Notifications\ResetRequestNotification;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Notification;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNotSame;
use function Tests\Common\Helpers\assertResponse;

it('tests password reset process', function (): void {
    // process contains these steps:
    // 1. password reset request
    // 2. password reset

    $user = User::factory()
        ->ofPassword('Test.123')
        ->create();

    $previousPasswordHash = $user->password;

    assertDatabaseEmpty(Token::class);

    // request password reset
    $response = postJson(route('api.password.request_reset'), [
        'email' => $user->email,
    ]);

    assertResponse($response, ResponseCodeEnum::SUCCESS);

    assertDatabaseHas(Token::class, [
        'type' => TokenTypeEnum::RESET_PASSWORD->value,
        'user_id' => $user->id,
    ]);

    Notification::assertSentTo($user, ResetRequestNotification::class);

    /** @var Token $token */
    $token = Token::query()
        ->where('type', '=', TokenTypeEnum::RESET_PASSWORD->value)
        ->where('user_id', '=', $user->id)
        ->first();

    // now reset password
    $response = postJson(route('api.password.reset', [
        'token' => $token->secret_token,
    ]), [
        'password' => 'Test.1234',
        'passwordConfirm' => 'Test.1234',
    ]);

    $token->refresh();

    $user->refresh();

    assertResponse($response, ResponseCodeEnum::SUCCESS);
    assertNotSame($previousPasswordHash, $user->password);

    // token should be marked as used
    assertNotNull($token->used_at);

    Notification::assertSentTo($user, ChangedNotification::class);
});
