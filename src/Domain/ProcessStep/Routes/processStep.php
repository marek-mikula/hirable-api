<?php

declare(strict_types=1);

use Domain\ProcessStep\Https\Controllers\ProcessStepController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [ProcessStepController::class, 'index'])->name('index');
    Route::post('/', [ProcessStepController::class, 'store'])->name('store');
    Route::delete('/{processStep}', [ProcessStepController::class, 'delete'])
        ->whereNumber('processStep')
        ->name('delete');
});
