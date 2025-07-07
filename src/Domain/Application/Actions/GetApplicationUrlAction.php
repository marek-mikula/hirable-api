<?php

declare(strict_types=1);

namespace Domain\Application\Actions;

use App\Actions\Action;
use Domain\Candidate\Enums\SourceEnum;

class GetApplicationUrlAction extends Action
{
    public function handle(SourceEnum $source, string $token): string
    {
        return frontendLink('/apply?token={token}', ['token' => sprintf('%s.%s', $source->getTokenPrefix(), $token)]);
    }
}
