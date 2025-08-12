<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionCancelApprovalController;
use Domain\Position\Http\Controllers\PositionApprovalDecideController;
use Domain\Position\Http\Controllers\PositionCandidateSetStepController;
use Domain\Position\Http\Controllers\PositionController;
use Domain\Position\Http\Controllers\PositionDuplicateController;
use Domain\Position\Http\Controllers\PositionExternalApprovalController;
use Domain\Position\Http\Controllers\PositionKanbanController;
use Domain\Position\Http\Controllers\PositionProcessStepController;
use Domain\Position\Http\Controllers\PositionSetProcessStepOrderController;
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
        Route::patch('/set-process-step-order', PositionSetProcessStepOrderController::class)->name('set_process_step_order');

        Route::prefix('/approvals')->as('approvals.')->group(function (): void {
            Route::patch('/{positionApproval}/decide', PositionApprovalDecideController::class)->whereNumber('positionApproval')->name('decide');
        });

        Route::prefix('/process-steps')->as('process_steps.')->group(function (): void {
            Route::post('/', [PositionProcessStepController::class, 'store'])->name('store');

            Route::prefix('/{positionProcessStep}')->whereNumber('positionProcessStep')->group(function (): void {
                Route::delete('/', [PositionProcessStepController::class, 'delete'])->name('delete');
                Route::patch('/', [PositionProcessStepController::class, 'update'])->name('update');
            });
        });

        Route::prefix('/candidates')->as('candidates.')->group(function (): void {
            Route::prefix('/{positionCandidate}')->whereNumber('positionCandidate')->group(function (): void {
                Route::patch('/set-step', PositionCandidateSetStepController::class)->name('set_step');
            });
        });

        Route::get('/kanban', PositionKanbanController::class)->name('kanban');
    });
});

Route::middleware(['guest:sanctum', TokenMiddleware::apply(TokenTypeEnum::EXTERNAL_APPROVAL)])
    ->prefix('/external-approvals')
    ->as('external_approval')
    ->group(function (): void {
        Route::get('/', [PositionExternalApprovalController::class, 'show'])->name('show');
        Route::patch('/', [PositionExternalApprovalController::class, 'decide'])->name('decide');
    });
