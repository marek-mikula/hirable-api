<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileTypeEnum: string
{
    case TEMP = 'temp';

    public function getDomain(): FileDomainEnum
    {
        return match ($this) {
            self::TEMP => FileDomainEnum::TEMP,
        };
    }
}
