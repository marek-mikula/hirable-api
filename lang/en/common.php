<?php

declare(strict_types=1);

use App\Enums\LanguageEnum;
use Domain\Candidate\Enums\GenderEnum;

return [

    'you' => 'You',
    'copy' => 'Copy',

    'language' => [
        LanguageEnum::EN->value => 'English',
        LanguageEnum::CS->value => 'Czech',
    ],

    'gender' => [
        GenderEnum::MALE->value => 'Male',
        GenderEnum::FEMALE->value => 'Female',
    ]

];
