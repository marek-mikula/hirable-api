<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Token\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Models\Token;
use Support\Token\Repositories\Input\TokenStoreInput;
use Support\Token\Repositories\TokenRepositoryInterface;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;
use function Tests\Common\Helpers\assertDatetime;

/** @covers \Support\Token\Repositories\TokenRepository::store */
it('tests store method - valid minutes from input', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $user = User::factory()->create();

    assertDatabaseEmpty(Token::class);

    $validMinutes = 30;

    $email = 'example@example.com';

    $token = $repository->store(new TokenStoreInput(
        type: TokenTypeEnum::REGISTRATION,
        data: ['email' => $email],
        validMinutes: $validMinutes,
        user: $user,
    ));

    assertModelExists($token);
    assertTrue($token->user->is($user));
    assertSame($token->type, TokenTypeEnum::REGISTRATION);
    assertDatetime($token->valid_until, now()->addMinutes($validMinutes));
    assertSame($token->getDataValue('email'), $email);
});

/** @covers \Support\Token\Repositories\TokenRepository::store */
it('tests store method - valid minutes from config', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $type = TokenTypeEnum::REGISTRATION;

    $validMinutes = rand(1, 160);

    // set config value
    config()->set("token.validity.{$type->value}", $validMinutes);

    $token = $repository->store(new TokenStoreInput(
        type: $type,
    ));

    assertModelExists($token);
    assertDatetime($token->valid_until, now()->addMinutes($validMinutes));
});

/** @covers \Support\Token\Repositories\TokenRepository::findByTokenAndType */
it('tests findByTokenAndType method', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $uuid = Str::uuid()->toString();
    $type = TokenTypeEnum::REGISTRATION;

    $token = Token::factory()->ofType($type)->create(['token' => $uuid]);

    // create couple more tokens
    Token::factory()->count(5)->create();

    $foundToken = $repository->findByTokenAndType($uuid, $type);

    assertNotNull($foundToken);
    assertTrue($foundToken->is($token));
});

/** @covers \Support\Token\Repositories\TokenRepository::findByTokenAndType */
it('tests findByTokenAndType method - multiple types', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $uuid = Str::uuid()->toString();
    $type = TokenTypeEnum::REGISTRATION;

    $token = Token::factory()->ofType($type)->create(['token' => $uuid]);

    // create couple more tokens
    Token::factory()->count(5)->create();

    $foundToken = $repository->findByTokenAndType($uuid, TokenTypeEnum::EMAIL_VERIFICATION, $type);

    assertNotNull($foundToken);
    assertTrue($foundToken->is($token));
});

/** @covers \Support\Token\Repositories\TokenRepository::findLatestByTypeAndEmail */
it('tests findLatestByTypeAndEmail method', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $tokens = Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofData(['email' => 'example@example.com'])
        ->count(3)
        ->create();

    $latestId = $tokens->pluck('id')->max();

    $token = $repository->findLatestByTypeAndEmail(TokenTypeEnum::REGISTRATION, 'example@example.com');

    assertSame($latestId, $token?->id);
});

/** @covers \Support\Token\Repositories\TokenRepository::findLatestByTypeAndUser */
it('tests findLatestByTypeAndUser method', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $user = User::factory()->create();

    $tokens = Token::factory()
        ->ofType(TokenTypeEnum::REGISTRATION)
        ->ofUser($user)
        ->count(3)
        ->create();

    $latestId = $tokens->pluck('id')->max();

    $token = $repository->findLatestByTypeAndUser(TokenTypeEnum::REGISTRATION, $user);

    assertSame($latestId, $token?->id);
});

/** @covers \Support\Token\Repositories\TokenRepository::delete */
it('tests delete method', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $token = Token::factory()->create();

    $repository->delete($token);

    assertModelMissing($token);
});

/** @covers \Support\Token\Repositories\TokenRepository::markUsed */
it('tests markUsed method', function (): void {
    /** @var TokenRepositoryInterface $repository */
    $repository = app(TokenRepositoryInterface::class);

    $token = Token::factory()->create(['used_at' => null]);

    $token = $repository->markUsed($token);

    assertNotNull($token->used_at);
});
