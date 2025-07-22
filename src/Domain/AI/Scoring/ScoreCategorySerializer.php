<?php

declare(strict_types=1);

namespace Domain\AI\Scoring;

use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\AI\Services\AIConfigService;

class ScoreCategorySerializer
{
    public function __construct(
        private readonly AIConfigService $AIConfigService,
    ) {
    }

    public function serialize(): string
    {
        $result = [];

        foreach (ScoreCategoryEnum::cases() as $category) {
            $result[] = sprintf(
                '**%s**: %s',
                $category->value,
                $this->AIConfigService->getScoreCategoryDescription($category)
            );
        }

        return implode(PHP_EOL, $result);
    }
}
