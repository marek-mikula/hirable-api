<?php

declare(strict_types=1);

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'welcome'])->name('welcome');
