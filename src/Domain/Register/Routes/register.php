<?php

declare(strict_types=1);

use Domain\Register\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('guest:sanctum')->group(static function (): void {
    Route::post('/request', [RegisterController::class, 'request'])
        ->name('request');

    Route::post('/', [RegisterController::class, 'register'])
        ->middleware(TokenMiddleware::apply(TokenTypeEnum::REGISTRATION, TokenTypeEnum::INVITATION))
        ->name('register');
});
