<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Support\Classifier\Http\Controllers\ClassifierController;

Route::get('/', [ClassifierController::class, 'index'])->name('index');
Route::get('/{type}', [ClassifierController::class, 'list'])->name('list');
