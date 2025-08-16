<?php

declare(strict_types=1);

namespace Domain\Position\Data;

readonly class GeneratedPositionData
{
    /**
     * @param array<string,mixed> $values
     */
    public function __construct(
        public array $values
    ) {
    }
}
