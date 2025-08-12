<?php

declare(strict_types=1);

namespace Domain\AI\Services;

use Carbon\Carbon;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\Candidate\Enums\GenderEnum;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Illuminate\Support\Collection;
use Support\File\Models\File;

class FakeAIService implements AIServiceInterface
{
    public function extractCVData(File $cv): CVData
    {
        return CVData::from([
            'gender' => fake()->randomElement(GenderEnum::cases()),
            'birthDate' => Carbon::createFromFormat('Y-m-d', fake()->date),
            'instagram' => null,
            'github' => null,
            'portfolio' => null,
            'experience' => [],
        ]);
    }

    public function scoreCandidateFitOnPosition(Position $position, Candidate $candidate, Collection $files): array
    {
        $score = [];

        foreach (ScoreCategoryEnum::cases() as $scoreCategory) {
            $score[] = ScoreCategoryData::from([
                'category' => $scoreCategory,
                'score' => fake()->numberBetween(0, 100),
                'comment' => fake()->sentence,
            ]);
        }

        return $score;
    }
}
