<?php

return [

    'time' => env(
        key: 'FORMAT_TIME',
        default: 'H:i' // 10:34
    ),

    'time_seconds' => env(
        key: 'FORMAT_TIME_SECONDS',
        default: 'H:i:s' // 10:34:54
    ),

    'date' => env(
        key: 'FORMAT_DATE',
        default: 'j. n. Y' // 5. 1. 1999
    ),

    'datetime' => env(
        key: 'FORMAT_DATETIME',
        default: 'j. n. Y, H:i' // 5. 1. 1999, 10:34
    ),

    'datetime_seconds' => env(
        key: 'FORMAT_DATETIME_SECONDS',
        default: 'j. n. Y, H:i:s' // 5. 1. 1999, 10:34:54
    ),

];
