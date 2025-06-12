<?php

declare(strict_types=1);

use Domain\Password\Http\Controllers\PasswordRequestResetController;
use Domain\Password\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('guest:sanctum')->group(static function (): void {
    Route::post('/request-reset', PasswordRequestResetController::class)
        ->name('request_reset');

    Route::post('/reset', PasswordResetController::class)
        ->middleware(TokenMiddleware::apply(TokenTypeEnum::RESET_PASSWORD))
        ->name('reset');
});
