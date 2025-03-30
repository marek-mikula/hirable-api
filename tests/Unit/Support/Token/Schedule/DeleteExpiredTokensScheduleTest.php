<?php

namespace Tests\Unit\Support\Token\Schedule;

use App\Models\Token;
use Illuminate\Support\Facades\Queue;
use Support\Token\Jobs\DeleteExpiredTokensJob;
use Support\Token\Schedule\DeleteExpiredTokensSchedule;

/** @covers \Support\Token\Schedule\DeleteExpiredTokensSchedule::__invoke */
it('dispatches job to delete expired tokens', function (): void {
    Queue::fake([
        DeleteExpiredTokensJob::class,
    ]);

    DeleteExpiredTokensSchedule::call();

    Queue::assertNothingPushed();

    Token::factory()->expired()->create();

    DeleteExpiredTokensSchedule::call();

    Queue::assertPushed(DeleteExpiredTokensJob::class);
});
