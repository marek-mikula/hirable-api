<?php

use Domain\Password\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('guest:sanctum')->group(static function (): void {
    Route::post('/request-reset', [PasswordController::class, 'requestReset'])
        ->name('request_reset');

    Route::post('/reset', [PasswordController::class, 'reset'])
        ->middleware(TokenMiddleware::apply(TokenTypeEnum::RESET_PASSWORD))
        ->name('reset');
});
