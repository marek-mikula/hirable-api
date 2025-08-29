<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing;

use Domain\Application\TokenProcessing\Data\TokenPackage;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenInfoException;

class TokenPackageExtractorService
{
    public function __construct(
        private readonly TokenDataExtractorService $tokenDataExtractorService,
        private readonly TokenParserService $tokenParserService,
    ) {
    }

    /**
     * @throws UnableExtractTokenDataException
     * @throws UnableExtractTokenInfoException
     */
    public function extract(string $token): TokenPackage
    {
        $tokenInfo = $this->tokenParserService->fromUrlValue($token);
        $tokenData = $this->tokenDataExtractorService->extract($tokenInfo);

        return new TokenPackage(
            tokenInfo: $tokenInfo,
            tokenData: $tokenData,
        );
    }
}
