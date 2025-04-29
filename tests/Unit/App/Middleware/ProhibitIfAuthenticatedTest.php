<?php

declare(strict_types=1);

use App\Enums\ResponseCodeEnum;
use App\Http\Middleware\ProhibitIfAuthenticated;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\actingAs;
use function Tests\Common\Helpers\assertHttpException;
use function Tests\Common\Helpers\createRequest;

/** @covers \App\Http\Middleware\ProhibitIfAuthenticated::handle */
it('correctly passes when user is not logged in', function (): void {
    $request = createRequest(FormRequest::class, headers: [
        'Accept' => 'application/json',
    ]);

    /** @var ProhibitIfAuthenticated $middleware */
    $middleware = app(ProhibitIfAuthenticated::class);

    $middleware->handle($request, static fn () => response()->json(), 'api');
})->throwsNoExceptions();

/** @covers \App\Http\Middleware\ProhibitIfAuthenticated::handle */
it('correctly throws exception for json request when user is logged in', function (): void {
    $user = User::factory()->create();

    $request = createRequest(FormRequest::class, headers: [
        'Accept' => 'application/json',
    ]);

    /** @var ProhibitIfAuthenticated $middleware */
    $middleware = app(ProhibitIfAuthenticated::class);

    actingAs($user, 'api');

    assertHttpException(function () use ($middleware, $request): void {
        $middleware->handle($request, static fn () => response()->json(), 'api');
    }, ResponseCodeEnum::GUEST_ONLY);
});

/** @covers \App\Http\Middleware\ProhibitIfAuthenticated::handle */
it('correctly sends redirect for basic request when user is logged in', function (): void {
    $user = User::factory()->create();

    $request = createRequest(FormRequest::class);

    /** @var ProhibitIfAuthenticated $middleware */
    $middleware = app(ProhibitIfAuthenticated::class);

    actingAs($user, 'web');

    $response = $middleware->handle($request, static fn () => response()->json(), 'web');

    assertTrue($response->isRedirection());
});
