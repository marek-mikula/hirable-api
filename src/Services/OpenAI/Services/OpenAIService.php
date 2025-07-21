<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\Application\Models\Application;
use Illuminate\Support\Collection;
use Services\OpenAI\Actions\ExtractCVDataAction;
use Services\OpenAI\Actions\ScoreApplicationAction;
use Support\File\Models\File;

class OpenAIService implements AIServiceInterface
{
    public function extractCVData(File $cv): CVData
    {
        return ExtractCVDataAction::make()->handle($cv);
    }

    public function scoreApplication(Application $application, Collection $files): array
    {
        return ScoreApplicationAction::make()->handle($application, $files);
    }
}
