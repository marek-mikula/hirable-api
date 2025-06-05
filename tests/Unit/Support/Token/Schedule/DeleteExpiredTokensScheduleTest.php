<?php

declare(strict_types=1);

namespace Tests\Unit\Support\Token\Schedule;

use Illuminate\Support\Facades\Queue;
use Support\Token\Jobs\DeleteExpiredTokensJob;
use Support\Token\Models\Token;
use Support\Token\Schedule\DeleteExpiredTokensSchedule;

/** @covers \Support\Token\Schedule\DeleteExpiredTokensSchedule */
it('dispatches job to delete expired tokens', function (): void {
    $days = 10;

    config()->set('token.keep_days', $days);

    Queue::fake([
        DeleteExpiredTokensJob::class,
    ]);

    Token::factory()->expired(now()->subDays($days))->create();

    DeleteExpiredTokensSchedule::call();

    Queue::assertPushed(DeleteExpiredTokensJob::class);
});
