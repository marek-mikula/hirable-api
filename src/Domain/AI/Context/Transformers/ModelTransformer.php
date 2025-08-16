<?php

declare(strict_types=1);

namespace Domain\AI\Context\Transformers;

interface ModelTransformer
{
    public function transformField(string $model, string $field, mixed $value): mixed;
}
