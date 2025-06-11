<?php

declare(strict_types=1);

use Domain\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('/{user}')->whereNumber('user')->group(static function (): void {
        Route::patch('/', [UserController::class, 'update'])->name('update');
    });
});
