<?php

declare(strict_types=1);

use Domain\Candidate\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [CandidateController::class, 'index'])->name('index');
    Route::post('/', [CandidateController::class, 'store'])->name('store');

    Route::prefix('/{candidate}')->whereNumber('candidate')->group(static function (): void {
        Route::get('/', [CandidateController::class, 'show'])->name('show');
        Route::patch('/', [CandidateController::class, 'update'])->name('update');
    });
});
