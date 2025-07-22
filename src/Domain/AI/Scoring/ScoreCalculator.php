<?php

declare(strict_types=1);

namespace Domain\AI\Scoring;

use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\Position\Models\Position;

class ScoreCalculator
{
    /**
     * @param ScoreCategoryData[] $score
     */
    public function calculateTotalScore(Position $position, array $score): int
    {
        $total = 0;
        $totalWeights = 0;

        foreach ($score as $scoreData) {
            $weight = $scoreData->category->getWeight($position);
            $totalWeights += $weight;
            $total += $scoreData->score * $weight;
        }

        return (int) round($total / $totalWeights);
    }
}
