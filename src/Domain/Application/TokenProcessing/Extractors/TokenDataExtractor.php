<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Extractors;

use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Application\TokenProcessing\Data\TokenInfo;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;

interface TokenDataExtractor
{
    /**
     * @throws UnableExtractTokenDataException
     */
    public function extractData(TokenInfo $token): TokenData;
}
