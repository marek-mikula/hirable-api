<?php

declare(strict_types=1);

namespace Services\OpenAI\Actions;

use App\Actions\Action;
use App\Enums\LanguageEnum;
use Domain\AI\Context\CommonContexter;
use Domain\AI\Context\ModelContexter;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\AI\Scoring\ScoreCategorySerializer;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAI\Laravel\Facades\OpenAI;
use Services\OpenAI\Enums\PromptEnum;
use Services\OpenAI\Services\OpenAIConfigService;
use Services\OpenAI\Services\OpenAIFileManager;
use Support\File\Models\File;

class EvaluateCandidateAction extends Action
{
    public function __construct(
        private readonly ScoreCategorySerializer $categorySerializer,
        private readonly OpenAIConfigService $configService,
        private readonly CommonContexter $commonContexter,
        private readonly ModelContexter $modelContexter,
        private readonly OpenAIFileManager $fileManager,
    ) {
    }

    /**
     * @param Collection<File> $files
     * @return ScoreCategoryData[]
     */
    public function handle(Position $position, Candidate $candidate, Collection $files): array
    {
        $result = OpenAI::responses()->create([
            'model' => $this->configService->getModel(PromptEnum::EVALUATE_CANDIDATE),
            'prompt' => $this->configService->getPrompt(PromptEnum::EVALUATE_CANDIDATE, [
                'language' => __(sprintf('common.language.%s', $position->company->ai_output_language->value), locale: LanguageEnum::EN->value),
                'context' => $this->commonContexter->getCommonContext(),
                'categories' => $this->categorySerializer->serialize(),
                'position' => $this->modelContexter->getModelContext($position, [
                    PositionFieldEnum::NAME,
                    PositionFieldEnum::DESCRIPTION,
                    PositionFieldEnum::MIN_EDUCATION_LEVEL,
                    PositionFieldEnum::EDUCATION_FIELD,
                    PositionFieldEnum::SENIORITY,
                    PositionFieldEnum::EXPERIENCE,
                    PositionFieldEnum::HARD_SKILLS,
                    PositionFieldEnum::ORGANISATION_SKILLS,
                    PositionFieldEnum::TEAM_SKILLS,
                    PositionFieldEnum::TIME_MANAGEMENT,
                    PositionFieldEnum::COMMUNICATION_SKILLS,
                    PositionFieldEnum::LEADERSHIP,
                    PositionFieldEnum::LANGUAGE_REQUIREMENTS,
                ]),
            ]),
            'input' => [
                [
                    'role' => 'user',
                    'content' => $files->map(fn (File $file) => $this->fileManager->attachFile($file))->all()
                ]
            ]
        ]);

        try {
            $json = json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new \Exception('Could not parse JSON output.');
        }

        $score = (array) Arr::get($json, 'score', []);

        return array_map(function (array $item) {
            return ScoreCategoryData::from([
                'category' => ScoreCategoryEnum::from((string) Arr::get($item, 'category')),
                'score' => (int) Arr::get($item, 'score'),
                'comment' => (string) Arr::get($item, 'comment'),
            ]);
        }, $score);
    }
}
