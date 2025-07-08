<?php

declare(strict_types=1);

namespace Domain\Application\TokenProcessing\Exceptions;

class UnableExtractTokenInfoException extends \Exception
{
    public function __construct(string $token)
    {
        parent::__construct(sprintf('Unable to extract token info from token %s.', $token));
    }
}
