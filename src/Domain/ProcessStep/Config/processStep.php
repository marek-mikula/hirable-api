<?php

declare(strict_types=1);

use Domain\ProcessStep\Enums\ProcessStepEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Default process steps for position
    |--------------------------------------------------------------------------
    |
    | Here we may configure the default process steps
    | for position. These process steps are created when
    | position is moved to opened state.
    |
    | Each key can have an array value which represents
    | attributes which are then stored in the database.
    |
    */

    'default_steps' => [
        ProcessStepEnum::NEW->value,
        ProcessStepEnum::SCREENING->value,
        ProcessStepEnum::SHORTLIST->value,
        ProcessStepEnum::INTERVIEW->value => [
            'round' => 1,
        ],
        ProcessStepEnum::OFFER_SENT->value,
        ProcessStepEnum::OFFER_ACCEPTED->value,
        ProcessStepEnum::PLACEMENT->value,
        ProcessStepEnum::REJECTED->value,
        ProcessStepEnum::WITHDRAWN->value,
    ],

];
