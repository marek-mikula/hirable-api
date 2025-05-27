<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionController;
use Domain\Position\Http\Controllers\PositionSuggestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [PositionController::class, 'index'])->name('index');
    Route::post('/', [PositionController::class, 'store'])->name('store');

    Route::prefix('/suggest')->as('suggest.')->group(function (): void {
        Route::get('/departments', [PositionSuggestController::class, 'suggestDepartments'])->name('departments');
    });

    Route::prefix('/{position}')->whereNumber('position')->group(function (): void {
        Route::get('/', [PositionController::class, 'show'])->name('show');
    });
});
