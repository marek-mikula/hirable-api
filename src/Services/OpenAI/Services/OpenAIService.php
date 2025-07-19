<?php

declare(strict_types=1);

namespace Services\OpenAI\Services;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Domain\AI\Contracts\AIServiceInterface;
use Domain\AI\Data\CVData;
use Domain\AI\Data\CVDataExperience;
use Domain\Candidate\Enums\GenderEnum;
use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use Services\OpenAI\Enums\PromptEnum;
use Support\File\Models\File;

class OpenAIService implements AIServiceInterface
{
    public function __construct(
        private readonly OpenAIConfigService $configService,
        private readonly OpenAIFileManager $fileManager,
    ) {
    }

    public function extractCVData(File $file): CVData
    {
        $result = OpenAI::responses()->create([
            'model' => $this->configService->getModel(),
            'prompt' => $this->configService->getPrompt(PromptEnum::EXTRACT_CV_DATA),
            'input' => [
                [
                    'role' => 'user',
                    'content' => [
                        $this->fileManager->attachFile($file)
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

        if (!empty($birthDate)) {
            try {
                $birthDate = Carbon::createFromFormat('Y-m-d', $birthDate);
            } catch (InvalidFormatException) {
                $birthDate = null;
            }
        }

        if (!empty($gender)) {
            $gender = match ($gender) {
                'male' => GenderEnum::MALE,
                'female' => GenderEnum::FEMALE,
            };
        }

        if (!empty($experience) && is_array($experience)) {
            $experience = array_map(function (array $item): CVDataExperience {
                $from = Arr::get($item, 'from');
                $to = Arr::get($item, 'to');
                $type = Arr::get($item, 'type');

                if (!empty($from)) {
                    try {
                        $from = Carbon::createFromFormat('Y-m-d', $from);
                    } catch (InvalidFormatException) {
                        $from = null;
                    }
                }

                if (!empty($to)) {
                    try {
                        $to = Carbon::createFromFormat('Y-m-d', $to);
                    } catch (InvalidFormatException) {
                        $to = null;
                    }
                }

                return CVDataExperience::from([
                    'position' => (string) Arr::get($item, 'position'),
                    'organisation' => (string) Arr::get($item, 'organisation'),
                    'from' => $from,
                    'to' => $to,
                    'type' => empty($type) ? null : (string) $type,
                ]);
            }, $experience);
        }

        return CVData::from([
            'gender' => empty($gender) ? null : $gender,
            'birthDate' => empty($birthDate) ? null : $birthDate,
            'instagram' => empty($instagram) ? null : (string) $instagram,
            'github' => empty($github) ? null : (string) $github,
            'portfolio' => empty($portfolio) ? null : (string) $portfolio,
            'experience' => $experience,
        ]);
    }
}
