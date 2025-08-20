<?php

declare(strict_types=1);

namespace Services\OpenAI\Actions;

use App\Actions\Action;
use Domain\AI\Context\ModelContexter;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use Services\OpenAI\Enums\PromptEnum;
use Services\OpenAI\Services\OpenAIConfigService;
use Services\OpenAI\Services\OpenAIFileManager;
use Services\OpenAI\Services\OpenAIJsonTransformer;
use Support\File\Models\File;

class ExtractCVDataAction extends Action
{
    public function __construct(
        private readonly OpenAIJsonTransformer $jsonTransformer,
        private readonly OpenAIConfigService $configService,
        private readonly OpenAIFileManager $fileManager,
        private readonly ModelContexter $modelContexter,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function handle(File $cv): array
    {
        $result = OpenAI::responses()->create([
            'model' => $this->configService->getModel(PromptEnum::EXTRACT_CV_DATA),
            'prompt' => $this->configService->getPrompt(PromptEnum::EXTRACT_CV_DATA, [
                'attributes' => $this->modelContexter->getModelContext(Candidate::class, [
                    CandidateFieldEnum::GENDER,
                    CandidateFieldEnum::INSTAGRAM,
                    CandidateFieldEnum::GITHUB,
                    CandidateFieldEnum::PORTFOLIO,
                    CandidateFieldEnum::BIRTH_DATE,
                    CandidateFieldEnum::EXPERIENCE,
                    CandidateFieldEnum::TAGS,
                ])
            ]),
            'input' => [
                [
                    'role' => 'user',
                    'content' => [
                        $this->fileManager->attachFile($cv)
                    ]
                ]
            ]
        ]);

        try {
            $json = json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new \Exception(sprintf('Cannot parse json: %s', $result->outputText));
        }

        $attributes = Arr::get($json, 'attributes', []);

        $attributes = $this->jsonTransformer->transform($attributes);

        return collect($attributes)
            ->mapWithKeys(function (array $attribute): array {
                $key = Arr::get($attribute, 'key');
                $value = Arr::get($attribute, 'value');
                return [$key => $value];
            })
            ->all();
    }
}
