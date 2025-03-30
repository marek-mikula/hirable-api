<?php

namespace Domain\Company\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case LECTURER = 'lecturer';
    case USER = 'user';
}
