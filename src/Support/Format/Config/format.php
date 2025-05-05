<?php

declare(strict_types=1);

return [

    'time' => (string) env(
        key: 'FORMAT_TIME',
        default: 'H:i' // 10:34
    ),

    'time_seconds' => (string) env(
        key: 'FORMAT_TIME_SECONDS',
        default: 'H:i:s' // 10:34:54
    ),

    'date' => (string) env(
        key: 'FORMAT_DATE',
        default: 'j. n. Y' // 5. 1. 1999
    ),

    'datetime' => (string) env(
        key: 'FORMAT_DATETIME',
        default: 'j. n. Y, H:i' // 5. 1. 1999, 10:34
    ),

    'datetime_seconds' => (string) env(
        key: 'FORMAT_DATETIME_SECONDS',
        default: 'j. n. Y, H:i:s' // 5. 1. 1999, 10:34:54
    ),

];
