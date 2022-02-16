<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\ToolController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'tool/{tool}',
        'as'     => 'tool.',
    ],
    function (): void {
        Route::get('add', [ToolController::class, 'create'])
            ->name('create');

        Route::post('', [ToolController::class, 'store'])
            ->name('store');

        Route::get('edit', [ToolController::class, 'edit'])
            ->name('edit');

        Route::put('update', [ToolController::class, 'update'])
            ->name('update');

        Route::put('publish', [ToolController::class, 'publish'])
            ->name('publish');

        Route::put('unpublish', [ToolController::class, 'unpublish'])
            ->name('unpublish');
    }
);

Route::resource('tool', ToolController::class)
    ->only(['index']);
