<?php

namespace Support\Token\Jobs;

use App\Jobs\ScheduleJob;
use App\Models\Token;
use App\Repositories\Token\TokenRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DeleteExpiredTokensJob extends ScheduleJob
{
    public function handle(TokenRepositoryInterface $tokenRepository): void
    {
        Token::query()
            ->readyToDelete()
            ->chunk(50, static function (Collection $tokens) use ($tokenRepository): void {
                /** @var Token $token */
                foreach ($tokens as $token) {
                    $tokenRepository->delete($token);
                }
            });
    }
}
