<?php

namespace Tests\Unit\App\Models;

use App\Models\Token;
use Mockery\MockInterface;
use Support\Token\Actions\GetTokenLinkAction;

use function Pest\Laravel\mock;
use function Pest\Laravel\travel;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/** @covers \App\Models\Token::link */
it('correctly handles url attribute', function (): void {
    $url = 'https://www.seznam.cz';

    GetTokenLinkAction::shouldRun()->andReturn($url);

    $token = new Token();

    assertSame($url, $token->link);
});

/** @covers \App\Models\Token::secretToken */
it('correctly handles secretToken attribute', function (): void {
    $token = new Token();

    $token->token = 'Hello there';

    mock('encrypter', function (MockInterface $mock) use ($token): void {
        $mock->shouldReceive('encryptString')->with($token->token)->andReturn('Ciphered token');
    });

    assertSame('Ciphered token', $token->secret_token);
});

/** @covers \App\Models\Token::isExpired */
it('correctly handles isExpired attribute', function (): void {
    $token = new Token();

    $minutes = 30;

    $token->valid_until = now()->addMinutes($minutes);

    assertFalse($token->is_expired);

    travel($minutes)->minutes();

    assertTrue($token->is_expired);
});
