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
        'email' => 'Email address',
        'phone' => 'Phone number',
        'name' => 'Name',
        'firstname' => 'Firstname',
        'lastname' => 'Lastname',
        'title' => 'Name',
        'description' => 'Description',
        'documents' => 'Documents',
        'document' => 'Document',
        'type' => 'Type',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'deleted_at' => 'Deleted at',
    ],

    'user' => [
        'timezone' => 'Timezone',
        'language' => 'Language',
        'password' => 'Password',
        'current_password' => 'Current password',
        'password_confirm' => 'Password confirmation',
        'new_password' => 'New password',
        'prefix' => 'Titles before name',
        'postfix' => 'Titles after name',
    ],

    'company' => [
        'name' => 'Company name',
        'id_number' => 'Company ID number',
        'email' => 'Company contact e-mail address',
        'website' => 'Company website',
    ],

];
