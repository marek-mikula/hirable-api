<?php

declare(strict_types=1);

use Domain\ProcessStep\Enums\StepEnum;

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
        StepEnum::NEW->value,
        StepEnum::SCREENING->value,
        StepEnum::SHORTLIST->value,
        StepEnum::OFFER_SENT->value,
        StepEnum::OFFER_ACCEPTED->value,
        StepEnum::PLACEMENT->value,
        StepEnum::REJECTED->value,
        StepEnum::WITHDRAWN->value,
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

    'steps_placement' => StepEnum::SHORTLIST->value,

];
