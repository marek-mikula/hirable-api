<?php

declare(strict_types=1);

namespace Support\Classifier\Services;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierConfigService
{
    public function supportsTranslation(ClassifierTypeEnum $type): bool
    {
        return config(sprintf('classifier.types.%s.translate', $type->value), false) === true;
    }

    public function getSeeder(ClassifierTypeEnum $type): string
    {
        $seederClass = config(sprintf('classifier.types.%s.seeder', $type->value));

        if (empty($seederClass)) {
            throw new \Exception(sprintf('Missing seeder class in config for type %s.', $type->value));
        }

        return (string) $seederClass;
    }

    public function getOrder(ClassifierTypeEnum $type): ?array
    {
        return config(sprintf('classifier.types.%s.order', $type->value));
    }
}
