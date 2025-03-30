<?php

namespace Support\Grid\Enums;

enum PerPageEnum: int
{
    case FIFTY = 50;
    case HUNDRED = 100;
    case HUNDRED_FIFTY = 150;
    case TWO_HUNDRED = 200;
    case TWO_HUNDRED_FIFTY = 250;
    case THREE_HUNDRED = 300;

    public static function default(): PerPageEnum
    {
        return PerPageEnum::HUNDRED;
    }
}
