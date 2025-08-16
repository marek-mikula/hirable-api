<?php

declare(strict_types=1);

namespace Domain\AI\Contracts;

use Domain\AI\Data\CVData;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Support\File\Models\File;

interface AIServiceInterface
{
    /**
     * Extracts additional candidate information from his CV
     */
    public function extractCVData(File $cv): CVData;

    /**
     * Evaluates candidate based on his uploaded files
     * and position he is applying to
     */
    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array;

    /**
     * Generate position attributes based on user's prompt
     * @return array<string,mixed> key is name of the attribute
     */
    public function generatePositionFromPrompt(User $user, string $prompt): array;

    /**
     * Generates position attributes based on user's uploaded JD
     * @return array<string,mixed> key is name of the attribute
     */
    public function generatePositionFromFile(User $user, UploadedFile $file): array;
}
