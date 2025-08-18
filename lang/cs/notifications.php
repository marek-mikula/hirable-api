<?php

declare(strict_types=1);

use Domain\Notification\Enums\NotificationTypeEnum;

return [

    'common' => [
        'salutation' => 'Zdravíčko',
        'regards' => 'S pozdravem',
        'signature' => 'tým :application',
        'link' => 'Pokud odkaz tlačítka nefunguje, použijte místo toho tento link: *:link*.',
        'rights' => 'Všechna práva vyhrazena.',
    ],

    NotificationTypeEnum::REGISTER_REQUEST->value => [
        'mail' => [
            'subject' => 'Dokončete registraci a začněte používat :application 🚀',
            'body' => [
                'line1' => 'Jsme nadšeni, že jste se k nám rozhodli připojit! K dokončení registrace použijte tlačítko níže!',
                'line2' => 'Odkaz je platný do **:validity**.',
                'action' => 'Dokončit registraci',
            ],
        ],
    ],

    NotificationTypeEnum::REGISTER_REGISTERED->value => [
        'mail' => [
            'subject' => 'Hurá! 🎉 Vítejte v :application!',
            'body' => [
                'line1' => 'Děkujeme, že jste se připojili k :application! Jsme rádi, že Vás máme mezi sebou, a těšíme se, že Vám nabídneme skvělý zážitek.',
            ],
        ],
    ],

    NotificationTypeEnum::VERIFICATION_VERIFY_EMAIL->value => [
        'mail' => [
            'subject' => '📧 Ověřte svou e-mailovou adresu',
            'body' => [
                'line1' => 'Potřebujeme ověřit Vaši e-mailovou adresu. Pro dokončení procesu ověření použijte tlačítko níže.',
                'line2' => 'Odkaz je platný do **:validity**.',
                'action' => 'Ověřit e-mailovou adresu',
            ],
        ],
    ],

    NotificationTypeEnum::VERIFICATION_EMAIL_VERIFIED->value => [
        'mail' => [
            'subject' => '🎉 Vaše e-mailová adresa byla ověřena',
            'body' => [
                'line1' => 'Vaše e-mailová adresa byla úspěšně ověřena! Můžete se plně ponořit do naší platformy a prozkoumat vše, co nabízí.',
            ],
        ],
    ],

    NotificationTypeEnum::PASSWORD_CHANGED->value => [
        'mail' => [
            'subject' => 'Heslo bylo změněno',
            'body' => [
                'line1' => 'Tímto Vám oznamujeme, že Vaše heslo bylo úspěšně změněno.',
                'line2' => 'Pokud jste heslo měnili Vy, tuto zprávu můžete ignorovat.',
                'line3' => 'Pokud jste tuto změnu neprovedli Vy nebo se domníváte, že k Vašemu účtu mohl získat přístup někdo jiný, neprodleně nás kontaktujte.',
            ],
        ],
    ],

    NotificationTypeEnum::PASSWORD_RESET_REQUEST->value => [
        'mail' => [
            'subject' => 'Žádost o obnovení hesla',
            'body' => [
                'line1' => 'Obdrželi jsme žádost o obnovení hesla k Vašemu účtu. Pro resetování hesla použijte tlačítko níže.',
                'line2' => 'Odkaz je platný do **:validity**.',
                'line3' => 'Pokud jste o obnovení hesla nežádali, tuto zprávu ignorujte.',
                'action' => 'Obnovit heslo',
            ],
        ],
    ],

    NotificationTypeEnum::INVITATION_SENT->value => [
        'mail' => [
            'subject' => 'Byli jste pozváni do :application!',
            'body' => [
                'line1' => 'Byli jste pozváni k připojení do :application. K dokončení registrace použijte tlačítko níže.',
                'line2' => 'Odkaz je platný do **:validity**.',
                'line3' => 'Pokud nevíte, o co se jedná, tuto zprávu můžete bezpečně ignorovat.',
                'action' => 'Dokončit registraci',
            ],
        ],
    ],

    NotificationTypeEnum::INVITATION_ACCEPTED->value => [
        'mail' => [
            'subject' => '✅ Pozvánka přijata',
            'body' => [
                'line1' => 'Uživatel **:user** přijal Vaši pozvánku.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL->value => [
        'mail' => [
            'subject' => '👍 Pozice ke schválení - :position',
            'body' => [
                'line1_internal' => 'V aplikaci je nová pozice **:position** od uživatele **:user**, která potřebuje Váš souhlas. Pozici můžete schválit <a href=":link">zde</a>.',
                'line1_external' => 'Uživatel **:user** Váš přiřadil k pozici **:position** jako schvalovatele v aplikaci :application. Pozici můžete schválit <a href=":link">zde</a>.',
                'line2' => 'O schválení rozhodněte do **:date**.'
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_REJECTED->value => [
        'mail' => [
            'subject' => '🔴 Pozice zamítnuta - :position',
            'body' => [
                'line1_internal' => 'Pozice **:position** byla zamítnuta uživatelem **:user**.',
                'line1_external' => 'Pozice **:position** byla zamítnuta externím schvalovatelem **:user**.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_APPROVED->value => [
        'mail' => [
            'subject' => '🟢 Pozice schválena - :position',
            'body' => [
                'line1' => 'Vaše pozice **:position** byla úspěšně schválena všemi schvalovateli.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_CANCELED->value => [
        'mail' => [
            'subject' => '⚪ Schvalování zrušeno - :position',
            'body' => [
                'line1' => 'Schvalovací proces pozice **:position** by zrušen uživatelem **:user**.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_EXPIRED->value => [
        'mail' => [
            'subject' => '⏱️ Schvalování vypršelo - :position',
            'body' => [
                'line1' => 'Schvalovací proces pozice **:position** vypršel.',
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_APPROVAL_REMINDER->value => [
        'mail' => [
            'subject' => '👍 Pozice čeká na schválení - :position',
            'body' => [
                'line1' => 'Pozice **:position** stále čeká na Vaše rozhodnotí. Na pozici se můžete podívat <a href=":link">zde</a>',
                'line2' => 'O schválení rozhodněte do **:date**.'
            ],
        ],
    ],

    NotificationTypeEnum::POSITION_OPENED->value => [
        'mail' => [
            'subject' => '✅ Pozice otevřena pro nábor - :position',
            'body' => [
                'line1' => 'Pozice **:position**, kde jste přiřazen jako hiring manažer nebo náborář, byla otevřena pro nábor. Na pozici se můžete podívat <a href=":link">zde</a>',
            ],
        ],
    ],

    NotificationTypeEnum::APPLICATION_ACCEPTED->value => [
        'mail' => [
            'subject' => '✅ Přihláška přijata!',
            'body' => [
                'line1' => 'Vaše přihláška na pozici **:position** byla úspěšně přijata. Brzy Vás budeme kontaktovat. Děkujeme za Váš zájem a přejem hodně štěstí ve výběrovém řízení.',
            ],
        ],
    ],

];
