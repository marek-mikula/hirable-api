<?php

declare(strict_types=1);

namespace Domain\Application\Actions;

use App\Actions\Action;
use Domain\Candidate\Enums\SourceEnum;

class GetReferralFormUrlAction extends Action
{
    public function handle(string $token): string
    {
        return frontendLink('/referral?token={token}', ['token' => sprintf('%s.%s', SourceEnum::REFERRAL->getTokenPrefix(), $token)]);
    }
}
