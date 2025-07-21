<?php

declare(strict_types=1);

namespace Domain\AI\Scoring\Data;

use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Spatie\LaravelData\Data;

class CategoryScoreData extends Data
{
    public ScoreCategoryEnum $category;

    public int $score;

    public string $comment;
}
