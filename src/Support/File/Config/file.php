<?php

declare(strict_types=1);

use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;
use Support\File\Enums\FileTypeEnum;

return [

    'rules' => [
        FileTypeEnum::POSITION_FILE->value => [
            'extensions' => [
                'pdf',
                'docx',
                'xlsx',
            ],
            'max_size' => '2MB',
            'max_files' => 5,
        ],
        FileTypeEnum::POSITION_GENERATE_FROM_FILE->value => [
            'extensions' => [
                'pdf',
                'docx',
            ],
            'max_size' => '1MB',
        ],
        FileTypeEnum::CANDIDATE_CV->value => [
            'extensions' => [
                'pdf',
                'docx',
            ],
            'max_size' => '2MB'
        ],
        FileTypeEnum::CANDIDATE_OTHER->value => [
            'extensions' => [
                'pdf',
                'docx',
                'xlsx',
                'jpg',
                'jpeg',
                'png',
            ],
            'max_size' => '2MB',
            'max_files' => 5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model folders
    |--------------------------------------------------------------------------
    |
    | Here we may configure the name of the folders for specific eloquent models.
    |
    */

    'model_folders' => [
        Candidate::class => 'candidates',
        Position::class => 'positions'
    ],

];
