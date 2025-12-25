<?php

declare(strict_types=1);

namespace Domain\Candidate\Http\Request\Data;

use Support\File\Data\FileData;

readonly class CandidateStoreData
{
    /**
     * @param FileData[] $cvs
     */
    public function __construct(
        public ?array $cvs,
        public ?int $positionId,
    ) {
    }
}
