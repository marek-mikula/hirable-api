<?php

use Domain\Company\Http\Controllers\CompanyController;
use Domain\Company\Http\Controllers\CompanyInvitationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::patch('/', [CompanyController::class, 'update'])
        ->name('update');

    Route::prefix('/invitations')->as('invitation.')->group(static function (): void {
        Route::post('/', [CompanyInvitationController::class, 'store'])
            ->name('store');
    });
});
