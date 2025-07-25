<?php

declare(strict_types=1);

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
        Position::class => 'positions'
    ],

];
