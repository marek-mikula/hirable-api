<?php

declare(strict_types=1);

namespace Support\Classifier\Models\Builders;

use App\Models\Builders\Builder;
use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierBuilder extends Builder
{
    public function whereType(ClassifierTypeEnum $type): static
    {
        return $this->where('type', $type->value);
    }
}
