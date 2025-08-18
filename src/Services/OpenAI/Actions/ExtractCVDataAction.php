<?php

declare(strict_types=1);

namespace Services\OpenAI\Actions;

use App\Actions\Action;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Domain\AI\Data\CVData;
use Domain\AI\Data\CVDataExperience;
use Domain\Candidate\Enums\GenderEnum;
use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use Services\OpenAI\Enums\PromptEnum;
use Services\OpenAI\Services\OpenAIConfigService;
use Services\OpenAI\Services\OpenAIFileManager;
use Support\File\Models\File;

class ExtractCVDataAction extends Action
{
    public function __construct(
        private readonly OpenAIConfigService $configService,
        private readonly OpenAIFileManager $fileManager,
    ) {
    }

    public function handle(File $cv): CVData
    {
        $result = OpenAI::responses()->create([
            'model' => $this->configService->getModel(PromptEnum::EXTRACT_CV_DATA),
            'prompt' => $this->configService->getPrompt(PromptEnum::EXTRACT_CV_DATA),
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
            throw new \Exception('Could not parse JSON output.');
        }

        $gender = Arr::get($json, 'gender');
        $birthDate = Arr::get($json, 'birthDate');
        $instagram = Arr::get($json, 'instagram');
        $github = Arr::get($json, 'github');
        $portfolio = Arr::get($json, 'portfolio');
        $experience = Arr::get($json, 'experience', []);
        $tags = Arr::get($json, 'tags', []);

        return CVData::from([
            'gender' => $this->formatGender($gender),
            'birthDate' => $this->formatDate($birthDate),
            'instagram' => empty($instagram) ? null : (string) $instagram,
            'github' => empty($github) ? null : (string) $github,
            'portfolio' => empty($portfolio) ? null : (string) $portfolio,
            'experience' => $this->formatExperience($experience),
            'tags' => $tags,
        ]);
    }

    private function formatGender(?string $gender): ?GenderEnum
    {
        return match ($gender) {
            'male' => GenderEnum::MALE,
            'female' => GenderEnum::FEMALE,
            default => null,
        };
    }

    private function formatDate(?string $value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value);
        } catch (InvalidFormatException) {
            return null;
        }
    }

    /**
     * @return CVDataExperience[]
     */
    private function formatExperience(mixed $value): array
    {
        if (empty($value) || !is_array($value)) {
            return [];
        }

        return array_map(function (array $item): CVDataExperience {
            $from = Arr::get($item, 'from');
            $to = Arr::get($item, 'to');
            $type = Arr::get($item, 'type');

            return CVDataExperience::from([
                'position' => (string) Arr::get($item, 'position'),
                'organisation' => (string) Arr::get($item, 'organisation'),
                'from' => $this->formatDate($from),
                'to' => $this->formatDate($to),
                'type' => empty($type) ? null : (string) $type,
            ]);
        }, $value);
    }
}
