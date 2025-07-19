<?php

declare(strict_types=1);

namespace Domain\AI\Contracts;

use Domain\AI\Data\CVData;
use Support\File\Models\File;

interface AIServiceInterface
{
    public function extractCVData(File $file): CVData;
}
