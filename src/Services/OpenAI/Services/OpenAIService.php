<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Illuminate\Support\Collection;
use Services\OpenAI\Actions\ExtractCVDataAction;
use Services\OpenAI\Actions\ScoreCandidateAction;
use Support\File\Models\File;

class OpenAIService implements AIServiceInterface
{
    public function extractCVData(File $cv): CVData
    {
        return ExtractCVDataAction::make()->handle($cv);
    }

    public function scoreCandidateFitOnPosition(Position $position, Candidate $candidate, Collection $files): array
    {
        return ScoreCandidateAction::make()->handle($position, $candidate, $files);
    }
}
