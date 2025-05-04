<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Auth\Services;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\AuthRequest;
use Domain\Auth\Http\Requests\Data\LoginData;
use Domain\Auth\Services\AuthService;
use Domain\User\Models\User;

use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\be;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertHttpException;
use function Tests\Common\Helpers\createRequest;

/** @covers \Domain\Auth\Services\AuthService::login */
it('tests login method - not existing user', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    $data = LoginData::from([
        'email' => 'admin@example.com',
        'password' => 'test',
        'rememberMe' => false,
    ]);

    assertHttpException(function () use ($service, $data): void {
        $service->login($data);
    }, ResponseCodeEnum::INVALID_CREDENTIALS);
});

/** @covers \Domain\Auth\Services\AuthService::login */
it('tests login method - invalid password', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    $password = 'test';

    $user = User::factory()->ofPassword($password)->create();

    $data = LoginData::from([
        'email' => $user->email,
        'password' => 'lmao',
        'rememberMe' => false,
    ]);

    assertHttpException(function () use ($service, $data): void {
        $service->login($data);
    }, ResponseCodeEnum::INVALID_CREDENTIALS);
});

/** @covers \Domain\Auth\Services\AuthService::login */
it('tests login method - not verified email', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    $password = 'test';

    $user = User::factory()
        ->emailNotVerified()
        ->ofPassword($password)
        ->create();

    $data = LoginData::from([
        'email' => $user->email,
        'password' => $password,
        'rememberMe' => false,
    ]);

    assertHttpException(function () use ($service, $data): void {
        $service->login($data);
    }, ResponseCodeEnum::EMAIL_VERIFICATION_NEEDED);
});

/** @covers \Domain\Auth\Services\AuthService::login */
it('tests login method - successful login', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    assertGuest('api');

    $password = 'test';

    $user = User::factory()->ofPassword($password)->create();

    $data = LoginData::from([
        'email' => $user->email,
        'password' => $password,
        'rememberMe' => false,
    ]);

    assertTrue($user->is($service->login($data)));

    assertAuthenticatedAs($user, 'api');
});

/** @covers \Domain\Auth\Services\AuthService::loginWithModel */
it('tests loginWithModel method - successful login', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    assertGuest('api');

    $user = User::factory()->create();

    $service->loginWithModel($user);

    assertAuthenticatedAs($user, 'api');
});

/** @covers \Domain\Auth\Services\AuthService::logout */
it('tests logout method - successful logout', function (): void {
    /** @var AuthService $service */
    $service = app(AuthService::class);

    $user = User::factory()->create();

    be($user, 'api');

    assertAuthenticatedAs($user, 'api');

    $request = createRequest(AuthRequest::class, loadSession: true);

    $service->logout($request);

    assertGuest('api');
});
