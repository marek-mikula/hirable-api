<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionApprovalDecideController;
use Domain\Position\Http\Controllers\PositionApprovalCancelController;
use Domain\Position\Http\Controllers\PositionController;
use Domain\Position\Http\Controllers\PositionExternalApprovalController;
use Domain\Position\Http\Controllers\PositionFileController;
use Domain\Position\Http\Controllers\PositionSuggestController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [PositionController::class, 'index'])->name('index');
    Route::post('/', [PositionController::class, 'store'])->name('store');

    Route::prefix('/suggest')->as('suggest.')->group(function (): void {
        Route::get('/departments', [PositionSuggestController::class, 'suggestDepartments'])->name('departments');
    });

    Route::prefix('/{position}')->whereNumber('position')->group(function (): void {
        Route::get('/', [PositionController::class, 'show'])->name('show');
        Route::patch('/', [PositionController::class, 'update'])->name('update');

        Route::prefix('/files')->as('files.')->group(function (): void {
            Route::delete('/{file}', [PositionFileController::class, 'destroy'])->whereNumber('file')->name('destroy');
        });

        Route::prefix('/approvals')->as('approvals.')->group(function (): void {
            Route::post('/cancel', PositionApprovalCancelController::class)->name('cancel');
            Route::patch('/{approval}/decide', PositionApprovalDecideController::class)->whereNumber('approval')->name('decide');
        });
    });
});

Route::middleware(['guest:sanctum', TokenMiddleware::apply(TokenTypeEnum::EXTERNAL_APPROVAL)])
    ->prefix('/external-approvals')
    ->as('external_approval')
    ->group(function (): void {
        Route::get('/', [PositionExternalApprovalController::class, 'show'])->name('show');
        Route::patch('/', [PositionExternalApprovalController::class, 'decide'])->name('decide');
    });
