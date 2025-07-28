<?php

declare(strict_types=1);

use Domain\ProcessStep\Enums\ProcessStepEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Fixed steps
    |--------------------------------------------------------------------------
    |
    | Here we may configure the default fixed steps,
    | which are created for every position in specified
    | order.
    |
    | Steps are generated in this order when position is
    | opened along with configurable steps.
    |
    */

    'fixed_steps' => [
        ProcessStepEnum::NEW->value,
        ProcessStepEnum::SCREENING->value,
        ProcessStepEnum::SHORTLIST->value,
        ProcessStepEnum::OFFER_SENT->value,
        ProcessStepEnum::OFFER_ACCEPTED->value,
        ProcessStepEnum::PLACEMENT->value,
        ProcessStepEnum::REJECTED->value,
        ProcessStepEnum::WITHDRAWN->value,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurable steps placement
    |--------------------------------------------------------------------------
    |
    | Configurable steps are placed after specified fixed step when
    | position is opened.
    |
    | If `steps_placement` is SHORTLIST, then all configurable steps
    | (interview, test, custom steps defined by user, ...) are placed
    | after SHORTLIST step.
    |
    */

    'steps_placement' => ProcessStepEnum::SHORTLIST->value,

];
