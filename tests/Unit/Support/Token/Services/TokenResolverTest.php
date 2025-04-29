<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Token\Services;

use App\Models\Token;
use Support\Token\Services\TokenResolver;

use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertException;

/**
 * @covers \Support\Token\Services\TokenResolver::getToken
 * @covers \Support\Token\Services\TokenResolver::setToken
 */
it('correctly sets and gets token', function (): void {
    /** @var TokenResolver $resolver */
    $resolver = app(TokenResolver::class);

    $token = Token::factory()->create();

    assertException(function () use ($resolver): void {
        $resolver->getToken();
    }, function (\Exception $exception): void {
        assertStringContainsString('No token found!', $exception->getMessage());
    });

    $resolver->setToken($token);

    assertTrue($resolver->getToken()->is($token));

    /** @var TokenResolver $resolver */
    $resolver = app(TokenResolver::class); // test that resolver is singleton

    assertTrue($resolver->getToken()->is($token));
});
