<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Files
    |--------------------------------------------------------------------------
    |
    | List of available extensions, max. file size for CVs and other files and max. number of files.
    |
    */

    'files' => [

        'cv' => [
            'extensions' => [
                'pdf',
                'docx',
            ],
            'max_size' => '5MB'
        ],

        'other' => [
            'extensions' => [
                'pdf',
                'docx',
                'xlsx',
                'jpg',
                'jpeg',
                'png',
            ],
            'max_size' => '5MB',
            'max_files' => 5,
        ],

    ],

];
