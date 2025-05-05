<?php

declare(strict_types=1);

namespace Support\Classifier\Services;

use Support\Classifier\Models\Classifier;

class ClassifierTranslateService
{
    public function __construct(
        private readonly ClassifierConfigService $classifierConfigService,
    ) {
    }

    public function translate(Classifier $classifier): string
    {
        if (!$this->classifierConfigService->supportsTranslation($classifier->type)) {
            return $classifier->value;
        }

        return __($this->getTranslationKey($classifier));
    }

    public function getTranslationKey(Classifier $classifier): string
    {
        return sprintf('classifiers.%s.%s', $classifier->type->value, $classifier->value);
    }
}
