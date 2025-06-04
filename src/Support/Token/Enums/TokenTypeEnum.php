<?php

declare(strict_types=1);

namespace Support\Token\Enums;

enum TokenTypeEnum: int
{
    case REGISTRATION = 1;
    case RESET_PASSWORD = 2;
    case EMAIL_VERIFICATION = 3;
    case INVITATION = 4;
    case EXTERNAL_APPROVAL = 5;
}
