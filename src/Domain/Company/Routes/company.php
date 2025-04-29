<?php

declare(strict_types=1);

use Domain\Company\Http\Controllers\CompanyController;
use Domain\Company\Http\Controllers\CompanyInvitationController;
use Domain\Company\Http\Controllers\CompanyUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::patch('/', [CompanyController::class, 'update'])
        ->name('update');

    Route::prefix('/invitations')->as('invitation.')->group(static function (): void {
        Route::get('/', [CompanyInvitationController::class, 'index'])
            ->name('index');
        Route::post('/', [CompanyInvitationController::class, 'store'])
            ->name('store');
    });

    Route::prefix('/users')->as('user.')->group(static function (): void {
        Route::get('/', [CompanyUserController::class, 'index'])
            ->name('index');
    });
});
