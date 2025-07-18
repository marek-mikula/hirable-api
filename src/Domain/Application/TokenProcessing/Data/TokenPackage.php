<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

use Spatie\LaravelData\Data;

class TokenPackage extends Data
{
    public TokenInfo $tokenInfo;

    public TokenData $tokenData;
}
