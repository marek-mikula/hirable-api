<?php

declare(strict_types=1);

use Domain\Application\Http\Controllers\ApplicationTokenInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/token-info', ApplicationTokenInfoController::class)->name('token_info');
