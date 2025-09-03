<?php

declare(strict_types=1);

namespace AIProviders\OpenAI\Actions;

use App\Actions\Action;
use Domain\AI\Context\ModelContexter;
use Domain\Candidate\Enums\CandidateFieldEnum;
use Domain\Candidate\Models\Candidate;
use OpenAI\Laravel\Facades\OpenAI;
use AIProviders\OpenAI\Enums\PromptEnum;
use AIProviders\OpenAI\Services\OpenAIConfigService;
use AIProviders\OpenAI\Services\OpenAIFileManager;
use Support\File\Models\File;

class ExtractCVDataAction extends Action
{
    public function __construct(
        private readonly OpenAIConfigService $configService,
        private readonly OpenAIFileManager $fileManager,
        private readonly ModelContexter $modelContexter,
    ) {
    }

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
            return json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new \Exception(sprintf('Cannot parse json: %s', $result->outputText));
        }
    }
}
