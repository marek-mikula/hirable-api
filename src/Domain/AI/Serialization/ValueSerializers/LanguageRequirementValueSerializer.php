<?php

declare(strict_types=1);

namespace Domain\AI\Serialization\ValueSerializers;

use App\Enums\LanguageEnum;
use Illuminate\Support\Arr;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Services\ClassifierTranslateService;

class LanguageRequirementValueSerializer implements ValueSerializer
{
    public function __construct(
        private readonly ClassifierTranslateService $classifierTranslateService,
    ) {
    }

    /**
     * @param array $value
     */
    public function serialize(mixed $value, array $config): ?string
    {
        $language = (string) Arr::get($value, 'language');
        $level = (string) Arr::get($value, 'level');

        return sprintf(
            '%s (%s)',
            $this->classifierTranslateService->translateValue(ClassifierTypeEnum::LANGUAGE, $language, LanguageEnum::EN->value),
            $this->classifierTranslateService->translateValue(ClassifierTypeEnum::LANGUAGE_LEVEL, $level, LanguageEnum::EN->value),
        );
    }
}
