<?php

declare(strict_types=1);

namespace Domain\AI\Contracts;

use Domain\AI\Data\CVData;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\Application\Models\Application;
use Illuminate\Support\Collection;
use Support\File\Models\File;

interface AIServiceInterface
{
    public function extractCVData(File $cv): CVData;

    /**
     * @param Collection<File> $files
     * @return ScoreCategoryData[]
     */
    public function scoreApplication(Application $application, Collection $files): array;
}
