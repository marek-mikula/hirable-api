<?php

declare(strict_types=1);

namespace Domain\AI\Contracts;

use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Support\File\Models\File;

interface AIProviderInterface
{
    /**
     * Extracts additional candidate information from his CV
     * @return array<string,mixed> decoded JSON
     */
    public function extractCVData(File $cv): array;

    /**
     * Evaluates candidate based on his uploaded files
     * and position he is applying to
     * @return array<string,mixed> decoded JSON
     */
    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array;

    /**
     * Generate position attributes based on user's prompt
     * @return array<string,mixed> decoded JSON
     */
    public function generatePositionFromPrompt(User $user, string $prompt): array;

    /**
     * Generates position attributes based on user's uploaded JD
     * @return array<string,mixed> decoded JSON
     */
    public function generatePositionFromFile(User $user, UploadedFile $file): array;
}
