<?php

use Domain\Verification\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::post('/verify-email', [VerificationController::class, 'verifyEmail'])
    ->middleware(TokenMiddleware::apply(TokenTypeEnum::EMAIL_VERIFICATION))
    ->name('verify_email');
