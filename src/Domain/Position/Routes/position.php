<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionSuggestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('/suggest')->as('suggest.')->group(function (): void {
        Route::get('/departments', [PositionSuggestController::class, 'suggestDepartments'])->name('departments');
    });
});
