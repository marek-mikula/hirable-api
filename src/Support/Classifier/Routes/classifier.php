<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Support\Classifier\Http\Controllers\ClassifierController;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/{type}', [ClassifierController::class, 'getList'])->name('list');
});
