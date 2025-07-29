<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionCancelApprovalController;
use Domain\Position\Http\Controllers\PositionApprovalDecideController;
use Domain\Position\Http\Controllers\PositionController;
use Domain\Position\Http\Controllers\PositionDuplicateController;
use Domain\Position\Http\Controllers\PositionExternalApprovalController;
use Domain\Position\Http\Controllers\PositionKanbanController;
use Domain\Position\Http\Controllers\PositionKanbanSettingsController;
use Domain\Position\Http\Controllers\PositionProcessStepController;
use Domain\Position\Http\Controllers\PositionSuggestDepartmentsController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [PositionController::class, 'index'])->name('index');
    Route::post('/', [PositionController::class, 'store'])->name('store');

    Route::get('/suggest-departments', PositionSuggestDepartmentsController::class)->name('suggest_departments');

    Route::prefix('/{position}')->whereNumber('position')->group(function (): void {
        Route::get('/', [PositionController::class, 'show'])->name('show');
        Route::patch('/', [PositionController::class, 'update'])->name('update');
        Route::delete('/', [PositionController::class, 'delete'])->name('delete');
        Route::post('/duplicate', PositionDuplicateController::class)->name('duplicate');

        Route::patch('/cancel-approval', PositionCancelApprovalController::class)->name('cancel');

        Route::prefix('/approvals')->as('approvals.')->group(function (): void {
            Route::patch('/{approval}/decide', PositionApprovalDecideController::class)->whereNumber('approval')->name('decide');
        });

        Route::prefix('/kanban')->as('kanban.')->group(function (): void {
            Route::get('/', PositionKanbanController::class)->name('index');
            Route::patch('/settings', PositionKanbanSettingsController::class)->name('settings');
        });

        Route::prefix('/process-steps')->as('process_steps.')->group(function (): void {
            Route::post('/', [PositionProcessStepController::class, 'store'])->name('store');
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
