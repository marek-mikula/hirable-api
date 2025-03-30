<?php

namespace Support\Token\Enums;

enum TokenTypeEnum: int
{
    case REGISTRATION = 1;
    case RESET_PASSWORD = 2;
    case EMAIL_VERIFICATION = 3;
    case INVITATION = 4;
}
