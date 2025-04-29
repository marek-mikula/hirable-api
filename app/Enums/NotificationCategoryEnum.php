<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationCategoryEnum
{
    // crucial notifications like password changes, auth tokens, etc.
    case CRUCIAL;

    // technical notifications like app outages, incidents, releases, etc.
    case TECHNICAL;

    // marketing notifications like sale, etc.
    case MARKETING;

    // application activity notifications, new courses, teams, etc.
    case APPLICATION;
}
