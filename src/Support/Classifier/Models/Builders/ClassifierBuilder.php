<?php

declare(strict_types=1);

namespace Support\Classifier\Models\Builders;

use App\Models\Builders\Builder;
use Support\Classifier\Enums\ClassifierTypeEnum;

class ClassifierBuilder extends Builder
{
    public function whereType(ClassifierTypeEnum|array $type): static
    {
        if (is_array($type)) {
            return $this->whereIn('type', collect($type)->pluck('value'));
        }

        return $this->where('type', $type->value);
    }

    public function whereValue(string $value): static
    {
        return $this->where('value', $value);
    }
}
