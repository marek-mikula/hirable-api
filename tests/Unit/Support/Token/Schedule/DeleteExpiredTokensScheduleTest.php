<?php

namespace Tests\Unit\Support\Token\Schedule;

use App\Models\Token;
use Illuminate\Support\Facades\Queue;
use Support\Token\Jobs\DeleteExpiredTokensJob;
use Support\Token\Schedule\DeleteExpiredTokensSchedule;

/** @covers \Support\Token\Schedule\DeleteExpiredTokensSchedule::__invoke */
it('dispatches job to delete expired tokens', function (): void {
    $days = 10;

    // set config value
    config()->set('token.keep_days', $days);

    Queue::fake([
        DeleteExpiredTokensJob::class,
    ]);

    DeleteExpiredTokensSchedule::call();

    Queue::assertNothingPushed();

    Token::factory()->expired(now())->create();

    DeleteExpiredTokensSchedule::call();

    Queue::assertNothingPushed();

    Token::factory()->expired(now()->subDays($days))->create();

    DeleteExpiredTokensSchedule::call();

    Queue::assertPushed(DeleteExpiredTokensJob::class);
});
