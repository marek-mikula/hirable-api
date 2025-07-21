<?php

declare(strict_types=1);

namespace Domain\AI\Scoring;

use Domain\AI\Scoring\Enums\ScoreCategoryEnum;

class CategorySerializer
{
    public function serialize(): string
    {
        $result = [];

        foreach (ScoreCategoryEnum::cases() as $category) {
            $description = config(sprintf('ai.score.categories.%s', $category->value));

            throw_if(empty($description), new \Exception(sprintf('Undefined category description for category %s.', $category->value)));

            $result[] = sprintf('**%s**: %s', $category->value, $description);
        }

        return implode(PHP_EOL, $result);
    }
}
