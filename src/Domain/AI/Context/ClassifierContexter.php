<?php

declare(strict_types=1);

namespace Domain\AI\Context;

use App\Enums\LanguageEnum;
use Support\Classifier\Enums\ClassifierTypeEnum;
use Support\Classifier\Models\Classifier;
use Support\Classifier\Repositories\ClassifierRepositoryInterface;
use Support\Classifier\Services\ClassifierTranslateService;

class ClassifierContexter
{
    public function __construct(
        private readonly ClassifierTranslateService $classifierTranslateService,
        private readonly ClassifierRepositoryInterface $classifierRepository,
    ) {
    }

    /**
     * @param ClassifierTypeEnum[] $types
     */
    public function getClassifierContext(array $types): string
    {
        $values = $this->classifierRepository->getValuesForTypes($types);

        foreach ($values as $type => $classifiers) {
            $values[$type] = $classifiers
                ->map(fn (Classifier $classifier) => [
                    'value' => $classifier->value,
                    'label' => $this->classifierTranslateService->translate($classifier, LanguageEnum::EN->value),
                ])
                ->all();
        }

        return json_encode($values);
    }
}
