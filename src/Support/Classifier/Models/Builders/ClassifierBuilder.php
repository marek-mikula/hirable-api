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
            return $this->whereIn('type', $type);
        }

        return $this->where('type', $type->value);
    }

    /**
     * @param string|string[] $value
     */
    public function whereValue(string|array $value): static
    {
        if (is_array($value)) {
            return $this->whereIn('value', $value);
        }

        return $this->where('value', $value);
    }
}
