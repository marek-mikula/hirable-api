<?php

declare(strict_types=1);

namespace Support\Classifier\Cache;

use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierCacheKeys
{
    public function getTypeCollectionCacheKey(ClassifierTypeEnum $type): string
    {
        return sprintf('classifiers.%s', $type->value);
    }
}
