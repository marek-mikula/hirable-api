<?php

declare(strict_types=1);

namespace Domain\Application\UseCases;

use App\UseCases\UseCase;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Scoring\Data\CategoryScoreData;
use Domain\AI\Scoring\ScoreCalculator;
use Domain\Application\Models\Application;
use Domain\Application\Repositories\ApplicationRepositoryInterface;
use Domain\Position\Models\Position;
use Illuminate\Support\Facades\DB;
use Support\File\Models\File;

class ScoreApplicationUseCase extends UseCase
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly AIServiceInterface $AIService,
        private readonly ScoreCalculator $scoreCounter,
    ) {
    }

    public function handle(Application $application): Application
    {
        $application->loadMissing([
            'files',
            'position',
            'position.company',
        ]);

        $allowedFiles = (array) config('ai.score.files');
        $files = $application->files->filter(fn (File $file) => in_array($file->extension, $allowedFiles));

        $score = $this->AIService->scoreApplication($application, $files);

        $totalScore = $this->scoreCounter->calculateTotalScore($application->position, $score);
        $mappedScore = $this->mapScore($application->position, $score);

        return DB::transaction(function () use (
            $application,
            $mappedScore,
            $totalScore,
        ): Application {
            return $this->applicationRepository->setScore($application, $mappedScore, $totalScore);
        }, attempts: 5);
    }

    private function mapScore(Position $position, array $score): array
    {
        return array_map(function (CategoryScoreData $data) use ($position) {
            return [
                'category' => $data->category->value,
                'score' => $data->score,
                'comment' => $data->comment,
                'weight' => $data->category->getWeight($position),
            ];
        }, $score);
    }
}
