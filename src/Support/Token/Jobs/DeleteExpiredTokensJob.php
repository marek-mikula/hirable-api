<?php

declare(strict_types=1);

namespace Support\Token\Jobs;

use App\Jobs\ScheduleJob;
use Illuminate\Database\Eloquent\Collection;
use Support\Token\Models\Token;
use Support\Token\Repositories\TokenRepositoryInterface;

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
