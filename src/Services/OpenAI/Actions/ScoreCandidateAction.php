<?php

declare(strict_types=1);

namespace Services\OpenAI\Actions;

use App\Actions\Action;
use App\Enums\LanguageEnum;
use Domain\AI\Context\CommonContexter;
use Domain\AI\Context\ModelSerializer;
use Domain\AI\Scoring\Data\ScoreCategoryData;
use Domain\AI\Scoring\Enums\ScoreCategoryEnum;
use Domain\AI\Scoring\ScoreCategorySerializer;
use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use OpenAI\Laravel\Facades\OpenAI;
use Services\OpenAI\Enums\PromptEnum;
use Services\OpenAI\Services\OpenAIConfigService;
use Services\OpenAI\Services\OpenAIFileManager;
use Support\File\Models\File;

class ScoreCandidateAction extends Action
{
    public function __construct(
        private readonly ScoreCategorySerializer $categorySerializer,
        private readonly OpenAIConfigService $configService,
        private readonly ModelSerializer $modelSerializer,
        private readonly CommonContexter $commonContexter,
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
            'model' => $this->configService->getModel(PromptEnum::SCORE_APPLICATION),
            'prompt' => $this->configService->getPrompt(PromptEnum::SCORE_APPLICATION, [
                'language' => __(sprintf('common.languages.%s', $position->company->ai_output_language->value), locale: LanguageEnum::EN->value),
                'context' => $this->commonContexter->getCommonContext(),
                'categories' => $this->categorySerializer->serialize(),
                'position' => $this->modelSerializer->serialize($position),
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
