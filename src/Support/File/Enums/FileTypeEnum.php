<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileTypeEnum: string
{
    case POSITION_FILE = 'position_file';
    case POSITION_GENERATE_FROM_FILE = 'position_generate_from';
    case CANDIDATE_CV = 'candidate_cv';
    case CANDIDATE_OTHER = 'candidate_other';
}
