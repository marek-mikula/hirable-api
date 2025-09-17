<?php

declare(strict_types=1);

namespace AIProviders\OpenAI\Actions;

use App\Actions\Action;
use App\Enums\LanguageEnum;
use Domain\AI\Context\ModelContexter;
use Domain\AI\Exceptions\InvalidJsonResponseException;
use Domain\Position\Enums\PositionFieldEnum;
use Domain\Position\Models\Position;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use OpenAI\Laravel\Facades\OpenAI;
use AIProviders\OpenAI\Enums\PromptEnum;
use AIProviders\OpenAI\Services\OpenAIConfigService;
use AIProviders\OpenAI\Services\OpenAIFileManager;

class GeneratePositionFromFileAction extends Action
{
    public function __construct(
        private readonly OpenAIConfigService $configService,
        private readonly ModelContexter $modelContexter,
        private readonly OpenAIFileManager $fileManager,
    ) {
    }

    /**
     * @throws InvalidJsonResponseException
     */
    public function handle(User $user, UploadedFile $file): array
    {
        $result = OpenAI::responses()->create([
            'model' => $this->configService->getModel(PromptEnum::GENERATE_POSITION_FROM_FILE),
            'prompt' => $this->configService->getPrompt(PromptEnum::GENERATE_POSITION_FROM_FILE, [
                'language' => __(sprintf('common.language.%s', $user->company->ai_output_language->value), locale: LanguageEnum::EN->value),
                'attributes' => $this->modelContexter->getModelContext(Position::class, [
                    PositionFieldEnum::NAME,
                    PositionFieldEnum::FIELD,
                    PositionFieldEnum::WORKLOADS,
                    PositionFieldEnum::EMPLOYMENT_RELATIONSHIPS,
                    PositionFieldEnum::EMPLOYMENT_FORMS,
                    PositionFieldEnum::JOB_SEATS_NUM,
                    PositionFieldEnum::DESCRIPTION,
                    PositionFieldEnum::SALARY_FROM,
                    PositionFieldEnum::SALARY_TO,
                    PositionFieldEnum::SALARY_FREQUENCY,
                    PositionFieldEnum::SALARY_CURRENCY,
                    PositionFieldEnum::SALARY_VAR,
                    PositionFieldEnum::BENEFITS,
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
                    PositionFieldEnum::TAGS,
                ])
            ]),
            'input' => [
                [
                    'role' => 'user',
                    'content' => [
                        $this->fileManager->attachUploadedFile($file),
                    ],
                ]
            ]
        ]);

        try {
            return json_decode((string) $result->outputText, true, flags: JSON_THROW_ON_ERROR);
        } catch (\Exception) {
            throw new InvalidJsonResponseException($result->id);
        }
    }
}
