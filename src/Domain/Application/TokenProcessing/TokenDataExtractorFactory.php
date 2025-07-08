<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing;

use Domain\Application\TokenProcessing\Extractors\InternTokenDataExtractor;
use Domain\Application\TokenProcessing\Extractors\PositionTokenDataExtractor;
use Domain\Application\TokenProcessing\Extractors\ReferralTokenDataExtractor;
use Domain\Application\TokenProcessing\Extractors\TokenDataExtractor;
use Domain\Candidate\Enums\SourceEnum;

class TokenDataExtractorFactory
{
    public function getExtractor(SourceEnum $source): TokenDataExtractor
    {
        $extractor = match ($source) {
            SourceEnum::POSITION => PositionTokenDataExtractor::class,
            SourceEnum::INTERN => InternTokenDataExtractor::class,
            SourceEnum::REFERRAL => ReferralTokenDataExtractor::class,
        };

        /** @var TokenDataExtractor $extractor */
        $extractor = app($extractor);

        return $extractor;
    }
}
