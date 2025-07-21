<?php

declare(strict_types=1);

namespace Support\Classifier\Services;

use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;

class ClassifierTranslateService
{
    public function __construct(
        private readonly ClassifierConfigService $classifierConfigService,
    ) {
    }

    public function translateValue(ClassifierTypeEnum $type, string $value, ?string $locale = null): string
    {
        if (!$this->classifierConfigService->supportsTranslation($type)) {
            return $value;
        }

        return __($this->getTranslationKey($type, $value), [], $locale);
    }

    public function translate(Classifier $classifier, ?string $locale = null): string
    {
        return $this->translateValue($classifier->type, $classifier->value, $locale);
    }

    public function getTranslationKey(ClassifierTypeEnum $type, string $value): string
    {
        return sprintf('classifiers.%s.%s', $type->value, $value);
    }
}
