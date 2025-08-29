<?php

declare(strict_types=1);

namespace Domain\Position\Models\Data;

readonly class OfferData
{
    public function __construct(
        public ?string $jobTitle,
    ) {
    }

    public function toArray(): array
    {
        return [
            'jobTitle' => $this->jobTitle,
        ];
    }
}
