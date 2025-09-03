<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

readonly class TokenPackage
{
    public function __construct(
        public TokenInfo $tokenInfo,
        public TokenData $tokenData,
    ) {
    }
}
