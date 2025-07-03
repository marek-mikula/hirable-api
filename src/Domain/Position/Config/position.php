<?php

declare(strict_types=1);

use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionRoleEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    |
    | List of position roles. The values represent a list of roles that
    | can fill a specific role in a given position.
    |
    */

    'roles' => [

        PositionRoleEnum::RECRUITER->value => [
            RoleEnum::ADMIN->value,
            RoleEnum::RECRUITER->value,
        ],

        PositionRoleEnum::HIRING_MANAGER->value => [
            RoleEnum::HIRING_MANAGER->value,
        ],

        PositionRoleEnum::APPROVER->value => [
            RoleEnum::ADMIN->value,
            RoleEnum::RECRUITER->value,
            RoleEnum::HIRING_MANAGER->value,
        ],

        PositionRoleEnum::EXTERNAL_APPROVER->value => [], // external approver is not bound to company roles

    ],

    'approval' => [

        /*
        |--------------------------------------------------------------------------
        | Remind days (in days)
        |--------------------------------------------------------------------------
        |
        | These options controls the number of days of approval reminder. N days
        | means the reminder is sent every N days.
        |
        */

        'remind_days' => 3,

    ]

];
