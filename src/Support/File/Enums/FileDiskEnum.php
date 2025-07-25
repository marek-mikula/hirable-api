<?php

declare(strict_types=1);

namespace Support\File\Enums;

enum FileDiskEnum: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';
    case S3 = 's3';
}
