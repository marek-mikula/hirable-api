<?php

declare(strict_types=1);

namespace Domain\AI\Context;

class CommonContexter
{
    public function getCommonContext(): string
    {
        return json_encode([
            'Datetime' =>  now()->toIso8601String(),
        ]);
    }
}
