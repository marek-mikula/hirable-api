<?php

declare(strict_types=1);

namespace Domain\Candidate\Services;

class CandidateConfigService
{
    public function getMaxTags(): int
    {
        return (int) config('candidate.max_tags');
    }
}
