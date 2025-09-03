<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

use Domain\Candidate\Enums\SourceEnum;

readonly class TokenInfo
{
    public function __construct(
        public SourceEnum $source,
        public string $token,
        public string $rawToken,
    ) {
    }
}
