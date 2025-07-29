<?php

declare(strict_types=1);

use Domain\Company\Enums\RoleEnum;
use Domain\Position\Enums\PositionRoleEnum;
use Domain\ProcessStep\Enums\StepEnum;

return [

    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    |
    | List of available extensions, max. size of one single file and max. number of files.
    |
    */

    'files' => [
        'extensions' => [
            'pdf',
            'docx',
            'xlsx',
        ],
        'max_size' => '5MB',
        'max_files' => 5,
    ],

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

    ],

    /*
    |--------------------------------------------------------------------------
    | Default configurable process steps
    |--------------------------------------------------------------------------
    |
    | Here we may configure the default configurable process
    | steps for position. These process steps are created when
    | position is moved to opened state.
    |
    | These steps does not include fixed steps, which
    | are configured in different config file (processStep.php).
    | These are only CONFIGURABLE steps!
    |
    */

    'default_configurable_process_steps' => [
        StepEnum::INTERVIEW->value,
    ],

];
