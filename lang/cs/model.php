<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Models attributes Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used mainly during validation to
    | translate model attribute names.
    |
    */

    'common' => [
        'id' => 'ID',
        'email' => 'Emailová adresa',
        'phone' => 'Telefonní číslo',
        'name' => 'Jméno',
        'firstname' => 'Křestní jméno',
        'lastname' => 'Příjmení',
        'title' => 'Název',
        'description' => 'Popis',
        'documents' => 'Dokumenty',
        'document' => 'Dokument',
        'type' => 'Typ',
        'language' => 'Jazyk',
        'note' => 'Poznámka',
        'role' => 'Role',
        'created_at' => 'Vytvořeno',
        'updated_at' => 'Aktualizováno',
        'deleted_at' => 'Smazáno',
    ],

    'user' => [
        'password' => 'Heslo',
        'old_password' => 'Staré heslo',
        'current_password' => 'Aktuální heslo',
        'password_confirm' => 'Potvrzení hesla',
        'new_password' => 'Nové heslo',
        'prefix' => 'Titul před jménem',
        'postfix' => 'Titul za jménem',
    ],

    'company' => [
        'name' => 'Název společnosti',
        'id_number' => 'IČO',
        'email' => 'Kontaktní e-mail společnosti',
        'website' => 'Webová stránka společnosti',
    ],

];
