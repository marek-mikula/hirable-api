<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Token\Jobs;

use App\Models\Token;
use Support\Token\Jobs\DeleteExpiredTokensJob;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

/** @covers \Support\Token\Jobs\DeleteExpiredTokensJob::handle */
it('deletes expired tokens', function (): void {
    $days = 10;

    // set config value
    config()->set('token.keep_days', $days);

    $token = Token::factory()->create();
    $expiredToken = Token::factory()->expired()->create();
    $expiredTokenSomeTime = Token::factory()->expired(now()->subDays($days))->create();

    app()->call([new DeleteExpiredTokensJob(), 'handle']);

    assertModelExists($token);
    assertModelExists($expiredToken);
    assertModelMissing($expiredTokenSomeTime);
});
