<?php

declare(strict_types=1);

use Support\Notification\Enums\NotificationTypeEnum;

return [

    NotificationTypeEnum::POSITION_APPROVAL->value => [
        'mail' => [
            'subject' => 'Pozice ke schválení - :position',
            'body' => [
                'line1' => 'V aplikaci je nová pozice ":position" od uživatele :user, která potřebuje Váš souhlas. Na pozici se můžete podívat <a href=":link">zde</a>',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_EXTERNAL_APPROVAL->value => [
        'mail' => [
            'subject' => 'Pozice ke schválení - :position',
            'body' => [
                'line1' => 'Uživatel :user Váš přiřadil k pozici ":position" jako schvalovatele v aplikaci :application. Na pozici se můžete podívat <a href=":link">zde</a>',
            ],
        ],
    ],

];
