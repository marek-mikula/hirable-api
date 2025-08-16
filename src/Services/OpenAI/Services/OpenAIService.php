<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Services\OpenAI\Actions\ExtractCVDataAction;
use Services\OpenAI\Actions\EvaluateCandidateAction;
use Services\OpenAI\Actions\GeneratePositionFromFileAction;
use Services\OpenAI\Actions\GeneratePositionFromPromptAction;
use Support\File\Models\File;

class OpenAIService implements AIServiceInterface
{
    public function extractCVData(File $cv): CVData
    {
        return ExtractCVDataAction::make()->handle($cv);
    }

    public function evaluateCandidate(Position $position, Candidate $candidate, Collection $files): array
    {
        return EvaluateCandidateAction::make()->handle($position, $candidate, $files);
    }

    public function generatePositionFromPrompt(User $user, string $prompt): array
    {
        return GeneratePositionFromPromptAction::make()->handle($user, $prompt);
    }

    public function generatePositionFromFile(User $user, UploadedFile $file): array
    {
        return GeneratePositionFromFileAction::make()->handle($user, $file);
    }
}
