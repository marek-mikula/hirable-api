<?php

declare(strict_types=1);

use Support\Token\Enums\TokenTypeEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Token keep days (in days)
    |--------------------------------------------------------------------------
    |
    | These options controls the number of days the expired or used tokens
    | are left in DB. After this number of days, the tokens get deleted.
    |
    */

    'keep_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Token validity (in minutes)
    |--------------------------------------------------------------------------
    |
    | These options controls the validity of created token for various
    | reasons. The number is the number of minutes the token is valid
    | for.
    |
    */

    'validity' => [

        TokenTypeEnum::REGISTRATION->value => (int) env(
            key: 'TOKEN_VALIDITY_REGISTRATION',
            default: 14 * 24 * 60 // 14 days
        ),

        TokenTypeEnum::RESET_PASSWORD->value => (int) env(
            key: 'TOKEN_VALIDITY_RESET_PASSWORD',
            default: 30 // 30 minutes
        ),

        TokenTypeEnum::EMAIL_VERIFICATION->value => (int) env(
            key: 'TOKEN_VALIDITY_EMAIL_VERIFICATION',
            default: 7 * 24 * 60 // 7 days
        ),

        TokenTypeEnum::INVITATION->value => (int) env(
            key: 'TOKEN_VALIDITY_INVITATION',
            default: 14 * 24 * 60 // 14 days
        ),

        TokenTypeEnum::EXTERNAL_APPROVAL->value => (int) env(
            key: 'TOKEN_VALIDITY_EXTERNAL_APPROVAL',
            default: 0 // approval process has custom validity time
        ),

    ],

    /*
    |--------------------------------------------------------------------------
    | Token throttling (in minutes)
    |--------------------------------------------------------------------------
    |
    | These options controls throttling of creating new
    | tokens. If user tries to create another token in
    | given minutes, he will be declined.
    |
    */

    'throttle' => [

        'registration' => (int) env(
            key: 'TOKEN_THROTTLE_REGISTRATION',
            default: 15 // 15 minutes
        ),

        'reset_password' => (int) env(
            key: 'TOKEN_THROTTLE_RESET_PASSWORD',
            default: 15 // 15 minutes
        ),

    ],

];
