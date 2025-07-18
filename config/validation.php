<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Phone validation
    |--------------------------------------------------------------------------
    |
    | Here you may configure if phone number should be validated
    | in Phone validation rule using Google's phone util.
    |
    */

    'phone' => (bool) env('VALIDATE_PHONE', true),

    /*
    |--------------------------------------------------------------------------
    | LinkedIn validation
    |--------------------------------------------------------------------------
    |
    | Here you may configure if URL should be validated
    | that it is a valid LinkedIn profile URL.
    |
    */

    'linkedin' => (bool) env('VALIDATE_LINKEDIN', true),

];
