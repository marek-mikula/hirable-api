<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Data;

use Domain\Candidate\Enums\SourceEnum;
use Spatie\LaravelData\Data;

class TokenInfo extends Data
{
    public SourceEnum $source;

    public string $token;

    public string $rawToken;
}
