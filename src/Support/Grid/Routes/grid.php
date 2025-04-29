<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Support\Grid\Http\Controllers\GridController;
use Support\Grid\Http\Controllers\GridSettingController;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('/{grid}')->group(static function (): void {
        Route::get('/', [GridController::class, 'show'])->name('show');

        Route::prefix('/settings')->as('setting.')->group(static function (): void {
            Route::patch('/', [GridSettingController::class, 'update'])->name('update');
            Route::patch('/set-column-width', [GridSettingController::class, 'setColumnWidth'])->name('set_column_width');
            Route::patch('/reset', [GridSettingController::class, 'reset'])->name('reset');
        });
    });
});
