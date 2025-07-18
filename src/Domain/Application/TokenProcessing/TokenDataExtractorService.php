<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing;

use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Application\TokenProcessing\Data\TokenInfo;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;

class TokenDataExtractorService
{
    public function __construct(
        private readonly TokenDataExtractorFactory $tokenExtractorFactory,
    ) {
    }

    /**
     * @throws UnableExtractTokenDataException
     */
    public function extract(TokenInfo $tokenInfo): TokenData
    {
        // get correct extractor by source type
        $extractor = $this->tokenExtractorFactory->getExtractor($tokenInfo->source);

        // extract data from the token info
        return $extractor->extractData($tokenInfo);
    }
}
