<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileTypeEnum: string
{
    case TEMP = 'temp';

    case POSITION_FILE = 'position_file';

    public function getDomain(): FileDomainEnum
    {
        return match ($this) {
            self::TEMP => FileDomainEnum::TEMP,
            self::POSITION_FILE => FileDomainEnum::POSITION,
        };
    }
}
