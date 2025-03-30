<?php

namespace Tests\Unit\Support\Token\Jobs;

use App\Models\Token;
use Support\Token\Jobs\DeleteExpiredTokensJob;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\assertModelMissing;

/** @covers \Support\Token\Jobs\DeleteExpiredTokensJob::handle */
it('deletes expired tokens', function (): void {
    $token = Token::factory()->create();
    $expiredToken = Token::factory()->expired()->create();

    app()->call([new DeleteExpiredTokensJob(), 'handle']);

    assertModelExists($token);
    assertModelMissing($expiredToken);
});
