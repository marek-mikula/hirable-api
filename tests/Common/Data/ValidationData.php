<?php

declare(strict_types=1);

namespace Tests\Common\Data;

class ValidationData
{
    public function __construct(
        public array|\Closure $data,
        public array $invalidInputs,
        public ?\Closure $before = null,
        public ?\Closure $after = null,
    ) {
    }
}
