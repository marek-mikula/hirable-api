<?php

declare(strict_types=1);

namespace Domain\AI\Exceptions;

class InvalidJsonResponseException extends \Exception
{
    public function __construct(private readonly string $id)
    {
        parent::__construct('Invalid JSON response from AI provider.');
    }

    public function context(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
