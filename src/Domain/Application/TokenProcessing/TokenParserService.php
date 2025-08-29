<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing;

use Domain\Application\TokenProcessing\Data\TokenInfo;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenInfoException;
use Domain\Candidate\Enums\SourceEnum;
use Illuminate\Support\Str;

class TokenParserService
{
    public const TOKEN_SEPARATOR = '.';

    public function toUrlValue(SourceEnum $source, string $token): string
    {
        return sprintf('%s%s%s', $source->getTokenPrefix(), self::TOKEN_SEPARATOR, $token);
    }

    /**
     * @throws UnableExtractTokenInfoException
     */
    public function fromUrlValue(string $token): TokenInfo
    {
        $prefix = Str::before($token, self::TOKEN_SEPARATOR);

        $source = SourceEnum::fromTokenPrefix($prefix);

        throw_if(empty($source), new UnableExtractTokenInfoException($token));

        $rawToken = Str::after($token, sprintf('%s%s', $source->getTokenPrefix(), self::TOKEN_SEPARATOR));

        throw_if(empty($rawToken), new UnableExtractTokenInfoException($token));

        return new TokenInfo(
            source: $source,
            token: $token,
            rawToken: $rawToken,
        );
    }
}
