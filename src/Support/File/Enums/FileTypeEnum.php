<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileTypeEnum: string
{
    case TEMP = 'temp';
    case POSITION_FILE = 'position_file';
    case APPLICATION_CV = 'application_cv';
    case APPLICATION_OTHER = 'application_other';
    case CANDIDATE_CV = 'candidate_cv';
    case CANDIDATE_OTHER = 'candidate_other';

    public function getDomain(): FileDomainEnum
    {
        return match ($this) {
            self::TEMP, self::APPLICATION_CV, self::APPLICATION_OTHER => FileDomainEnum::TEMP,
            self::POSITION_FILE => FileDomainEnum::POSITION,
            self::CANDIDATE_CV, self::CANDIDATE_OTHER => FileDomainEnum::CANDIDATE,
        };
    }
}
