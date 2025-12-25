<?php

declare(strict_types=1);

use App\Enums\LanguageEnum;
use Domain\Candidate\Enums\GenderEnum;

return [

    'you' => 'Vy',
    'copy' => 'Kopie',

    'language' => [
        LanguageEnum::EN->value => 'Angličtina',
        LanguageEnum::CS->value => 'Čeština',
    ],

    'gender' => [
        GenderEnum::MALE->value => 'Muž',
        GenderEnum::FEMALE->value => 'Žena',
    ],

    'assign_to_position' => 'Přiřadit na pozici',

];
