<?php

declare(strict_types=1);

namespace App\Enums;

enum EnvEnum: string
{
    case LOCAL = 'local';
    case TESTING = 'testing';
    case DEBUG = 'debug';
    case PRODUCTION = 'production';
}
