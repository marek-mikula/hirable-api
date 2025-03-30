<?php

namespace App\Enums;

enum EnvEnum: string
{
    case LOCAL = 'local';
    case TESTING = 'testing';
    case DEBUG = 'debug';
    case PRODUCTION = 'production';
}
