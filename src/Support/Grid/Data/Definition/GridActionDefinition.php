<?php

namespace Support\Grid\Data\Definition;

use Spatie\LaravelData\Data;

class GridActionDefinition extends Data
{
    public function __construct(
        public string $key,
        public string $label,
        public bool $needsRefresh,
    ) {}
}
