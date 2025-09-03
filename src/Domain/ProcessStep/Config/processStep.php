<?php

declare(strict_types=1);

use Domain\ProcessStep\Enums\StepEnum;
use Domain\Position\Enums\ActionTypeEnum;

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
        StepEnum::NEW->value => [
            'triggers_action' => null,
        ],
        StepEnum::SCREENING->value => [
            'triggers_action' => ActionTypeEnum::INTERVIEW->value,
        ],
        StepEnum::SHORTLIST->value => [
            'triggers_action' => null,
        ],
        StepEnum::OFFER->value => [
            'triggers_action' => ActionTypeEnum::OFFER->value,
        ],
        StepEnum::PLACEMENT->value => [
            'triggers_action' => ActionTypeEnum::START_OF_WORK->value,
        ],
        StepEnum::REJECTED->value => [
            'triggers_action' => ActionTypeEnum::REJECTION->value,
        ],
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
