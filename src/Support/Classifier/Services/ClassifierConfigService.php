<?php

declare(strict_types=1);

namespace Support\Classifier\Services;

use App\Services\Service;
use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierConfigService extends Service
{
    public function isCacheEnabled(): bool
    {
        return config('classifier.cache_enabled') === true;
    }

    public function getCacheTime(): int
    {
        $value = config('classifier.cache_time');

        if (empty($value) || (!is_int($value) && !is_numeric($value))) {
            throw new \Exception('Invalid classifier cache time. Value must be an integer or numeric string.');
        }

        return (int) $value;
    }

    public function supportsTranslation(ClassifierTypeEnum $type): bool
    {
        return config(sprintf('classifier.types.%s.translate', $type->value)) === true;
    }

    public function getSeeder(ClassifierTypeEnum $type): string
    {
        $seederClass = config(sprintf('classifier.types.%s.seeder', $type->value));

        if (empty($seederClass)) {
            throw new \Exception(sprintf('Missing classifier seeder class for type %s.', $type->value));
        }

        if (!class_exists($seederClass)) {
            throw new \Exception(sprintf('Invalid classifier seeder class for type %s. Class does not exist.', $type->value));
        }

        return (string) $seederClass;
    }

    public function getOrder(ClassifierTypeEnum $type): ?array
    {
        $order = config(sprintf('classifier.types.%s.order', $type->value));

        if (!is_null($order) && !is_array($order)) {
            throw new \Exception(sprintf('Invalid order for type %s. Value must be null or array.', $type->value));
        }

        return $order;
    }
}
