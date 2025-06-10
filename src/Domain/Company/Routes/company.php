<?php

declare(strict_types=1);

use Domain\Company\Http\Controllers\CompanyContactController;
use Domain\Company\Http\Controllers\CompanyContactSuggestCompaniesController;
use Domain\Company\Http\Controllers\CompanyController;
use Domain\Company\Http\Controllers\CompanyInvitationController;
use Domain\Company\Http\Controllers\CompanyUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('/{company}')->whereNumber('company')->group(static function (): void {
        Route::get('/', [CompanyController::class, 'show'])->name('show');
        Route::patch('/', [CompanyController::class, 'update'])->name('update');

        Route::prefix('/users')->as('users.')->group(static function (): void {
            Route::get('/', [CompanyUserController::class, 'index'])->name('index');
        });

        Route::prefix('/invitations')->as('invitations.')->group(static function (): void {
            Route::get('/', [CompanyInvitationController::class, 'index'])->name('index');
            Route::post('/', [CompanyInvitationController::class, 'store'])->name('store');
        });

        Route::prefix('/contacts')->as('contacts.')->group(static function (): void {
            Route::get('/', [CompanyContactController::class, 'index'])->name('index');
            Route::post('/', [CompanyContactController::class, 'store'])->name('store');
            Route::get('/suggest-companies', CompanyContactSuggestCompaniesController::class)->name('suggest_companies');

            Route::prefix('/{contact}')->whereNumber('contact')->group(static function (): void {
                Route::patch('/', [CompanyContactController::class, 'update'])->name('update');
                Route::delete('/', [CompanyContactController::class, 'delete'])->name('delete');
            });
        });
    });
});
