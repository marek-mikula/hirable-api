<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing;

use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenInfoException;

class TokenDataExtractorService
{
    public function __construct(
        private readonly TokenDataExtractorFactory $tokenExtractorFactory,
        private readonly TokenParserService $tokenParserService,
    ) {
    }

    /**
     * @throws UnableExtractTokenDataException
     * @throws UnableExtractTokenInfoException
     */
    public function extract(string $token): TokenData
    {
        // parse token from URL to data object
        $token = $this->tokenParserService->fromUrlValue($token);

        // get correct extractor by source type
        $extractor = $this->tokenExtractorFactory->getExtractor($token->source);

        // extract data from the token info
        return $extractor->extractData($token);
    }
}
