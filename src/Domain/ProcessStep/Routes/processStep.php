<?php

declare(strict_types=1);

use Domain\ProcessStep\Https\Controllers\ProcessStepController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::post('/', [ProcessStepController::class, 'store'])->name('store');
});
