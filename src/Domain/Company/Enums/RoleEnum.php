<?php

declare(strict_types=1);

namespace Domain\Company\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
