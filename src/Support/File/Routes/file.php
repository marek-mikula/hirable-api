<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Support\File\Http\Controllers\FileController;
use Support\File\Http\Controllers\FileDownloadController;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('/{file}')->whereNumber('file')->group(function (): void {
        Route::get('/', [FileController::class, 'show'])->name('show');
        Route::delete('/', [FileController::class, 'delete'])->name('delete');
        Route::get('/download', FileDownloadController::class)->name('download');
    });
});
