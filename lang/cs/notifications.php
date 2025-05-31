<?php

declare(strict_types=1);

use Support\Notification\Enums\NotificationTypeEnum;

return [

    NotificationTypeEnum::POSITION_APPROVAL->value => [
        'mail' => [
            'subject' => 'Pozice ke schválení :position',
            'body' => [
                'line1' => '',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL->value => [
        'mail' => [
            'subject' => 'Pozice ke schválení :position',
            'body' => [
                'line1' => '',
            ],
        ],
    ],

];
