<?php

declare(strict_types=1);

namespace Domain\AI\Context;

class CommonContexter
{
    public function getCommonContext(): string
    {
        $result = [];

        $result[] = ['label' => 'Datetime', 'value' => now()->toIso8601String()];

        return json_encode(['context' => $result]);
    }
}
