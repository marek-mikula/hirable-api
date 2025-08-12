<?php

declare(strict_types=1);

use Domain\Candidate\Models\Candidate;
use Domain\Position\Models\Position;

return [

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
