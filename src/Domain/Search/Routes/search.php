<?php

declare(strict_types=1);

use Domain\Search\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/company-users', [SearchController::class, 'companyUsers'])->name('company_users');
    Route::get('/company-contacts', [SearchController::class, 'companyContacts'])->name('company_contacts');
});
