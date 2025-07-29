<?php

declare(strict_types=1);

namespace Domain\AI\Scoring;

use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Services\AIConfigService;
use Domain\Position\Models\Position;

class ScoreCalculator
{
    public function __construct(
        private readonly AIConfigService $AIConfigService,
    ) {
    }

    /**
     * @param ScoreCategoryData[] $score
     */
    public function calculateTotalScore(Position $position, array $score): int
    {
        $weights = 0;
        $total = 0;

        $baseWeight = $this->AIConfigService->getScoreBaseWeight();

        foreach ($score as $scoreData) {
            $weight = $scoreData->category->getWeight($position) + $baseWeight;
            $weights += $weight;
            $total += $scoreData->score * $weight;
        }

        return (int) round($total / $weights);
    }
}
