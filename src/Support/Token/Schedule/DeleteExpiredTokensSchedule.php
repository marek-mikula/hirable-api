<?php

declare(strict_types=1);

namespace Support\Token\Schedule;

use App\Schedule\Schedule;
use Support\Token\Jobs\DeleteExpiredTokensJob;
use Support\Token\Models\Token;

class DeleteExpiredTokensSchedule extends Schedule
{
    public function __invoke(): void
    {
        if (!$this->shouldRun()) {
            return;
        }

        DeleteExpiredTokensJob::dispatch();
    }

    private function shouldRun(): bool
    {
        return Token::query()->readyToDelete()->exists();
    }
}
