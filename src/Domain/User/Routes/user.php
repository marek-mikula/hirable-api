<?php

use Domain\User\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [UserController::class, 'index'])->name('index');
});
