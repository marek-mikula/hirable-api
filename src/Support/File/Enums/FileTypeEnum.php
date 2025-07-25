<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileTypeEnum: string
{
    case POSITION_FILE = 'position_file';
    case CANDIDATE_CV = 'candidate_cv';
    case CANDIDATE_OTHER = 'candidate_other';
}
