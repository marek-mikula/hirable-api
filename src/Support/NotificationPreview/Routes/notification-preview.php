<?php

use Illuminate\Support\Facades\Route;
use Support\NotificationPreview\Controllers\Http\NotificationPreviewController;

Route::get('/', [NotificationPreviewController::class, 'index'])
    ->name('index');

Route::get('/{type}', [NotificationPreviewController::class, 'show'])
    ->name('show');

Route::get('/{type}/mail', [NotificationPreviewController::class, 'mail'])
    ->name('mail');
