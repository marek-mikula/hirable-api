<?php

declare(strict_types=1);

namespace Domain\AI\Context;

class CommonContexter
{
    public function getCommonContext(): string
    {
        $result = [];

        $result[] = sprintf('**Datetime**: %s', now()->toIso8601String());

        return implode(PHP_EOL, $result);
    }
}
