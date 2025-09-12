<?php

declare(strict_types=1);

use Domain\Position\Http\Controllers\PositionCancelApprovalController;
use Domain\Position\Http\Controllers\PositionApprovalDecideController;
use Domain\Position\Http\Controllers\PositionCandidateActionController;
use Domain\Position\Http\Controllers\PositionCandidateController;
use Domain\Position\Http\Controllers\PositionCandidateEvaluationController;
use Domain\Position\Http\Controllers\PositionCandidateEvaluationRequestController;
use Domain\Position\Http\Controllers\PositionCandidateSetPriorityController;
use Domain\Position\Http\Controllers\PositionCandidateSetStepController;
use Domain\Position\Http\Controllers\PositionCandidateShareController;
use Domain\Position\Http\Controllers\PositionController;
use Domain\Position\Http\Controllers\PositionDuplicateController;
use Domain\Position\Http\Controllers\PositionExternalApprovalController;
use Domain\Position\Http\Controllers\PositionGenerateFromFileController;
use Domain\Position\Http\Controllers\PositionGenerateFromPromptController;
use Domain\Position\Http\Controllers\PositionProcessStepController;
use Domain\Position\Http\Controllers\PositionProcessStepSetOrderController;
use Domain\Position\Http\Controllers\PositionSuggestDepartmentsController;
use Illuminate\Support\Facades\Route;
use Support\Token\Enums\TokenTypeEnum;
use Support\Token\Http\Middleware\TokenMiddleware;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [PositionController::class, 'index'])->name('index');
    Route::post('/', [PositionController::class, 'store'])->name('store');

    Route::get('/suggest-departments', PositionSuggestDepartmentsController::class)->name('suggest_departments');

    Route::post('/generate-from-prompt', PositionGenerateFromPromptController::class)->name('generate_from_prompt');
    Route::post('/generate-from-file', PositionGenerateFromFileController::class)->name('generate_from_file');

    Route::prefix('/{position}')->whereNumber('position')->group(function (): void {
        Route::get('/', [PositionController::class, 'show'])->name('show');
        Route::patch('/', [PositionController::class, 'update'])->name('update');
        Route::delete('/', [PositionController::class, 'delete'])->name('delete');
        Route::post('/duplicate', PositionDuplicateController::class)->name('duplicate');

        Route::patch('/cancel-approval', PositionCancelApprovalController::class)->name('cancel');

        Route::prefix('/approvals')->as('approvals.')->group(function (): void {
            Route::patch('/{positionApproval}/decide', PositionApprovalDecideController::class)->whereNumber('positionApproval')->name('decide');
        });

        Route::prefix('/process-steps')->as('process_steps.')->group(function (): void {
            Route::get('/', [PositionProcessStepController::class, 'index'])->name('index');
            Route::post('/', [PositionProcessStepController::class, 'store'])->name('store');
            Route::patch('/set-order', PositionProcessStepSetOrderController::class)->name('set_process_step_order');
            Route::prefix('/{positionProcessStep}')->whereNumber('positionProcessStep')->group(function (): void {
                Route::get('/', [PositionProcessStepController::class, 'show'])->name('show');
                Route::delete('/', [PositionProcessStepController::class, 'delete'])->name('delete');
                Route::patch('/', [PositionProcessStepController::class, 'update'])->name('update');
            });
        });

        Route::prefix('/candidates')->as('candidates.')->group(function (): void {
            Route::get('/', [PositionCandidateController::class, 'index'])->name('index');
            Route::prefix('/{positionCandidate}')->whereNumber('positionCandidate')->group(function (): void {
                Route::get('/', [PositionCandidateController::class, 'show'])->name('show');
                Route::patch('/set-step', PositionCandidateSetStepController::class)->name('set_step');
                Route::patch('/set-priority', PositionCandidateSetPriorityController::class)->name('set_priority');
                Route::prefix('/actions')->as('action.')->group(function (): void {
                    Route::post('/', [PositionCandidateActionController::class, 'store'])->name('store');
                    Route::prefix('/{positionCandidateAction}')->whereNumber('positionCandidateAction')->group(function (): void {
                        Route::patch('/', [PositionCandidateActionController::class, 'update'])->name('update');
                        Route::get('/', [PositionCandidateActionController::class, 'show'])->name('show');
                    });
                });
                Route::prefix('/shares')->as('share.')->group(function (): void {
                    Route::get('/', [PositionCandidateShareController::class, 'index'])->name('index');
                    Route::post('/', [PositionCandidateShareController::class, 'store'])->name('store');
                    Route::prefix('/{positionCandidateShare}')->whereNumber('positionCandidateShare')->group(function (): void {
                        Route::delete('/', [PositionCandidateShareController::class, 'delete'])->name('delete');
                    });
                });
                Route::prefix('/evaluations')->as('evaluation.')->group(function (): void {
                    Route::get('/', [PositionCandidateEvaluationController::class, 'index'])->name('index');
                    Route::post('/', [PositionCandidateEvaluationController::class, 'store'])->name('store');
                    Route::post('/request', PositionCandidateEvaluationRequestController::class)->name('request');
                    Route::prefix('/{positionCandidateEvaluation}')->whereNumber('positionCandidateEvaluation')->group(function (): void {
                        Route::patch('/', [PositionCandidateEvaluationController::class, 'update'])->name('update');
                        Route::delete('/', [PositionCandidateEvaluationController::class, 'delete'])->name('delete');
                    });
                });
            });
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
