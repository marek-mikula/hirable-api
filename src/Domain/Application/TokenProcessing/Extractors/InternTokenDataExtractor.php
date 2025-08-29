<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Extractors;

use Domain\Application\TokenProcessing\Data\TokenData;
use Domain\Application\TokenProcessing\Data\TokenInfo;
use Domain\Application\TokenProcessing\Exceptions\UnableExtractTokenDataException;
use Domain\Position\Repositories\PositionRepositoryInterface;

class InternTokenDataExtractor implements TokenDataExtractor
{
    public function __construct(
        private readonly PositionRepositoryInterface $positionRepository,
    ) {
    }

    public function extractData(TokenInfo $token): TokenData
    {
        $position = $this->positionRepository->findBy([
            'intern_token' => $token->rawToken,
        ]);

        throw_if(empty($position), new UnableExtractTokenDataException($token->token));

        return new TokenData(
            position: $position
        );
    }
}
