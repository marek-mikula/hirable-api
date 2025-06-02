<?php

declare(strict_types=1);

use Support\Notification\Enums\NotificationTypeEnum;

return [

    'common' => [
        'salutation' => 'Zdravíčko',
        'regards' => 'S pozdravem',
        'signature' => 'tým :application',
        'link' => 'Pokud odkaz tlačítka nefunguje, použijte místo toho tento link: *:link*.',
        'rights' => 'Všechna práva vyhrazena.',
    ],

    NotificationTypeEnum::POSITION_APPROVAL->value => [
        'mail' => [
            'subject' => '👍 Pozice ke schválení - :position',
            'body' => [
                'line1_internal' => 'V aplikaci je nová pozice ":position" od uživatele :user, která potřebuje Váš souhlas. Na pozici se můžete podívat <a href=":link">zde</a>',
                'line1_external' => 'Uživatel :user Váš přiřadil k pozici ":position" jako schvalovatele v aplikaci :application. Na pozici se můžete podívat <a href=":link">zde</a>',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_REJECTED->value => [
        'mail' => [
            'subject' => '❌ Pozice zamítnuta - :position',
            'body' => [
                'line1_internal' => 'Pozice ":position" byla zamítnuta uživatelem :user.',
                'line1_external' => 'Pozice ":position" byla zamítnuta externím schvalovatelem :user.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVED->value => [
        'mail' => [
            'subject' => '🎉 Pozice schválena - :position',
            'body' => [
                'line1' => 'Vaše pozice ":position" byla úspěšně schválena všemi schvalovateli.',
            ],
        ],
    ],

];
