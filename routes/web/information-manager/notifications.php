<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\NotificationController;

Route::group(
    [
        'prefix' => 'notifications',
        'as'     => 'notifications.',
    ],
    function (): void {
        Route::get('create/{tool?}', [NotificationController::class, 'create'])
            ->name('create');

        Route::post('', [NotificationController::class, 'send'])
            ->name('send');
    }
);
