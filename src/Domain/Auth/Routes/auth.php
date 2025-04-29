<?php

declare(strict_types=1);

use Domain\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/csrf', [CsrfCookieController::class, 'show'])
    ->name('csrf');

Route::middleware('guest:sanctum')->group(static function (): void {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/me', [AuthController::class, 'me'])
        ->name('me');

    Route::patch('/', [AuthController::class, 'update'])
        ->name('update');
});
