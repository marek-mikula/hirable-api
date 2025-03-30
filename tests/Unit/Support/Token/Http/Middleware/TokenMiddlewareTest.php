<?php

namespace Tests\Unit\Support\Token\Http\Middleware;

use App\Enums\ResponseCodeEnum;
use App\Models\Token;
use App\Repositories\Token\TokenRepositoryInterface;
use Illuminate\Contracts\Encryption\DecryptException;
use Mockery\MockInterface;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;
use Support\Token\Http\Requests\TokenRequest;
use Support\Token\Services\TokenResolver;

use function Pest\Laravel\mock;
use function PHPUnit\Framework\assertSame;
use function Tests\Common\Helpers\assertHttpException;
use function Tests\Common\Helpers\createRequest;

/** @covers \Support\Token\Http\Middleware\TokenMiddleware::handle */
it('denies missing token', function (): void {
    $request = createRequest(TokenRequest::class, method: 'POST');

    $next = function (): void {};

    /** @var TokenMiddleware $middleware */
    $middleware = app(TokenMiddleware::class);

    assertHttpException(function () use ($middleware, $request, $next): void {
        $middleware->handle($request, $next, TokenTypeEnum::REGISTRATION->value);
    }, ResponseCodeEnum::TOKEN_MISSING);
});

/** @covers \Support\Token\Http\Middleware\TokenMiddleware::handle */
it('denies invalid encrypted token', function (): void {
    $encryptedToken = 'encrypted_token';

    $request = createRequest(TokenRequest::class, data: ['token' => $encryptedToken], method: 'POST');

    $next = function (): void {};

    mock('encrypter', function (MockInterface $mock) use ($encryptedToken): void {
        $mock
            ->shouldReceive('decryptString')
            ->with($encryptedToken)
            ->once()
            ->andThrow(new DecryptException());
    });

    /** @var TokenMiddleware $middleware */
    $middleware = app(TokenMiddleware::class);

    assertHttpException(function () use ($middleware, $request, $next): void {
        $middleware->handle($request, $next, TokenTypeEnum::REGISTRATION->value);
    }, ResponseCodeEnum::TOKEN_CORRUPTED);
});

/** @covers \Support\Token\Http\Middleware\TokenMiddleware::handle */
it('denies not-existing token', function (): void {
    $encryptedToken = 'encrypted_token';
    $decryptedToken = 'decrypted_token';

    $request = createRequest(TokenRequest::class, data: ['token' => $encryptedToken], method: 'POST');

    $next = function (): void {};

    mock('encrypter', function (MockInterface $mock) use ($encryptedToken, $decryptedToken): void {
        $mock
            ->shouldReceive('decryptString')
            ->with($encryptedToken)
            ->once()
            ->andReturn($decryptedToken);
    });

    mock(TokenRepositoryInterface::class, function (MockInterface $mock) use ($decryptedToken): void {
        $mock
            ->shouldReceive('findByTokenAndType')
            ->with($decryptedToken, TokenTypeEnum::REGISTRATION)
            ->once()
            ->andReturnNull();
    });

    /** @var TokenMiddleware $middleware */
    $middleware = app(TokenMiddleware::class);

    assertHttpException(function () use ($middleware, $request, $next): void {
        $middleware->handle($request, $next, TokenTypeEnum::REGISTRATION->value);
    }, ResponseCodeEnum::TOKEN_INVALID);
});

/** @covers \Support\Token\Http\Middleware\TokenMiddleware::handle */
it('denies expired token', function (): void {
    $encryptedToken = 'encrypted_token';
    $decryptedToken = 'decrypted_token';

    $token = Token::factory()->ofType(TokenTypeEnum::REGISTRATION)->ofToken($decryptedToken)->expired()->create();

    $request = createRequest(TokenRequest::class, data: ['token' => $encryptedToken], method: 'POST');

    $next = function (): void {};

    mock('encrypter', function (MockInterface $mock) use ($encryptedToken, $decryptedToken): void {
        $mock
            ->shouldReceive('decryptString')
            ->with($encryptedToken)
            ->once()
            ->andReturn($decryptedToken);
    });

    mock(TokenRepositoryInterface::class, function (MockInterface $mock) use ($decryptedToken, $token): void {
        $mock
            ->shouldReceive('findByTokenAndType')
            ->with($decryptedToken, TokenTypeEnum::REGISTRATION)
            ->once()
            ->andReturn($token);
    });

    /** @var TokenMiddleware $middleware */
    $middleware = app(TokenMiddleware::class);

    assertHttpException(function () use ($middleware, $request, $next): void {
        $middleware->handle($request, $next, TokenTypeEnum::REGISTRATION->value);
    }, ResponseCodeEnum::TOKEN_INVALID);
});

/** @covers \Support\Token\Http\Middleware\TokenMiddleware::handle */
it('accepts correct token', function (): void {
    $encryptedToken = 'encrypted_token';
    $decryptedToken = 'decrypted_token';

    $token = Token::factory()->ofType(TokenTypeEnum::REGISTRATION)->ofToken($decryptedToken)->create();

    $request = createRequest(TokenRequest::class, data: ['token' => $encryptedToken], method: 'POST');

    $middlewareResponse = response()->json();

    $next = static fn () => $middlewareResponse;

    mock('encrypter', function (MockInterface $mock) use ($encryptedToken, $decryptedToken): void {
        $mock
            ->shouldReceive('decryptString')
            ->with($encryptedToken)
            ->once()
            ->andReturn($decryptedToken);
    });

    mock(TokenRepositoryInterface::class, function (MockInterface $mock) use ($decryptedToken, $token): void {
        $mock
            ->shouldReceive('findByTokenAndType')
            ->with($decryptedToken, TokenTypeEnum::REGISTRATION)
            ->once()
            ->andReturn($token);
    });

    mock(TokenResolver::class, function (MockInterface $mock) use ($token): void {
        $mock
            ->shouldReceive('setToken')
            ->once()
            ->with($token);
    });

    /** @var TokenMiddleware $middleware */
    $middleware = app(TokenMiddleware::class);

    $response = $middleware->handle($request, $next, TokenTypeEnum::REGISTRATION->value);

    assertSame((string) $middlewareResponse, (string) $response);
});
