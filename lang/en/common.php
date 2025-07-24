<?php

declare(strict_types=1);

use App\Enums\LanguageEnum;

return [

    'you' => 'You',
    'copy' => 'Copy',

    'languages' => [
        LanguageEnum::EN->value => 'English',
        LanguageEnum::CS->value => 'Czech',
    ],

];
