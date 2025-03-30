<?php

namespace App\Enums;

enum QueueEnum: string
{
    // jobs that are dispatched from a schedule
    case SCHEDULE = 'schedule';

    // jobs that sends out notifications
    case NOTIFICATIONS = 'notifications';

    // common jobs
    // - activity logs
    case COMMON = 'common';
}
