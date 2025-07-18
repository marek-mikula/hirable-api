<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Exceptions;

class UnableExtractTokenDataException extends \Exception
{
    public function __construct(string $token)
    {
        parent::__construct(sprintf('Unable to extract token data from token %s.', $token));
    }
}
