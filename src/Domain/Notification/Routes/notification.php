<?php

declare(strict_types=1);

use Domain\Notification\Http\Controllers\NotificationController;
use Domain\Notification\Http\Controllers\NotificationMarkAllReadController;
use Domain\Notification\Http\Controllers\NotificationMarkReadController;
use Domain\Notification\Http\Controllers\NotificationUnreadController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(static function (): void {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/unread', NotificationUnreadController::class)->name('unread');

    Route::patch('/mark-all-read', NotificationMarkAllReadController::class)
        ->name('mark_all_read');

    Route::patch('/{notification}/mark-read', NotificationMarkReadController::class)
        ->whereUuid('notification')
        ->name('mark_read');
});
