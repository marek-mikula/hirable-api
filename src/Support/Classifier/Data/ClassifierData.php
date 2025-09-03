<?php

declare(strict_types=1);

namespace Support\Classifier\Data;

readonly class ClassifierData
{
    public function __construct(
        public string $value,
        public string $label,
    ) {
    }
}
