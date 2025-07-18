<?php

declare(strict_types=1);

namespace Domain\Candidate\Services;

use App\Services\Service;

class CandidateConfigService extends Service
{
    public function getCvAllowedFileExtensions(): array
    {
        return (array) config('candidate.files.cv.extensions', []);
    }

    public function getCvMaxFileSize(): string
    {
        return (string) config('candidate.files.cv.max_size');
    }

    public function getOtherAllowedFileExtensions(): array
    {
        return (array) config('candidate.files.other.extensions', []);
    }

    public function getOtherMaxFileSize(): string
    {
        return (string) config('candidate.files.other.max_size');
    }

    public function getOtherMaxFiles(): int
    {
        return (int) config('candidate.files.other.max_files');
    }
}
