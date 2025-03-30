<?php

namespace Support\File\Enums;

enum FileDomainEnum: string
{
    case TEMP = 'temp';

    public function getDisk(): string
    {
        return match ($this) {
            self::TEMP => 'temp',
        };
    }
}
