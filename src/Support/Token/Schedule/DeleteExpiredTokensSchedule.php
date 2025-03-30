<?php

namespace Support\Token\Schedule;

use App\Models\Token;
use App\Schedule\Schedule;
use Support\Token\Jobs\DeleteExpiredTokensJob;

class DeleteExpiredTokensSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (! $this->shouldRun()) {
            return;
        }

        DeleteExpiredTokensJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return Token::query()->expired()->exists();
    }
}
