<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileDomainEnum: string
{
    case TEMP = 'temp';
    case POSITION = 'position';
    case CANDIDATE = 'candidate';

    public function getDisk(): string
    {
        return match ($this) {
            self::TEMP => 'temp',
            self::POSITION => 'positions',
            self::CANDIDATE => 'candidates',
        };
    }
}
