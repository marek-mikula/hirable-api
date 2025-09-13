<?php

declare(strict_types=1);

namespace Domain\Position\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\ScoreCalculator;
use Domain\AI\Services\AIConfigService;
use Domain\AI\Services\AIService;
use Domain\Position\Models\Position;
use Domain\Position\Models\PositionCandidate;
use Domain\Position\Repositories\PositionCandidateRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Support\File\Models\File;

class PositionCandidateEvaluateUseCase extends UseCase
{
    public function __construct(
        private readonly PositionCandidateRepositoryInterface $positionCandidateRepository,
        private readonly AIConfigService $AIConfigService,
        private readonly AIService $AIService,
        private readonly ScoreCalculator $scoreCounter,
    ) {
    }

    public function handle(PositionCandidate $positionCandidate): PositionCandidate
    {
        $positionCandidate->loadMissing([
            'candidate',
            'candidate.files',
            'position',
            'position.company',
        ]);

        $allowedFiles = $this->AIConfigService->getScoreFiles();

        // get only those files, that can be
        // serialized into AI request
        $files = $positionCandidate
            ->candidate
            ->files
            ->filter(fn (File $file): bool => in_array($file->extension, $allowedFiles));

        $score = $this->AIService->evaluateCandidate($positionCandidate->position, $positionCandidate->candidate, $files);

        $totalScore = $this->scoreCounter->calculateTotalScore($positionCandidate->position, $score);
        $mappedScore = $this->mapScore($positionCandidate->position, $score);

        return DB::transaction(fn (): PositionCandidate => $this->positionCandidateRepository->setScore($positionCandidate, $mappedScore, $totalScore), attempts: 5);
    }

    private function mapScore(Position $position, array $score): array
    {
        return array_map(fn (ScoreCategoryData $data): array => [
            'category' => $data->category->value,
            'score' => $data->score,
            'comment' => $data->comment,
            'weight' => $data->category->getWeight($position),
        ], $score);
    }
}
