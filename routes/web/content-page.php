<?php

declare(strict_types=1);

use App\Http\Controllers\ContentPageController;

Route::group([
    'middleware' => ['auth'],
], function (): void {
    Route::resource('content-page', ContentPageController::class)
        ->except(['show']);

    Route::get('page/{contentPage:slug}', [ContentPageController::class, 'show'])->name('content-page.show');
});
