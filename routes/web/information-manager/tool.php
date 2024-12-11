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

        Route::get('edit', [ToolController::class, 'edit'])
            ->name('edit');

        Route::put('update/{continue?}', [ToolController::class, 'update'])
            ->name('update');

        Route::put('publish', [ToolController::class, 'publish'])
            ->name('publish');

        Route::put('unpublish', [ToolController::class, 'unpublish'])
            ->name('unpublish');

        Route::put('publish-concept', [ToolController::class, 'publishConcept'])
            ->name('publish-concept');

        Route::put('discard-concept', [ToolController::class, 'discardConcept'])
            ->name('discard-concept');

        Route::get('log', [ToolController::class, 'log'])
            ->name('log');

        Route::post('cancel-edit', [ToolController::class, 'cancelEdit'])
            ->name('cancel-edit');

        Route::post('request-for-change', [ToolController::class, 'requestForChange'])
            ->name('request-for-change');

        Route::post('{continue?}', [ToolController::class, 'store'])
            ->name('store');
    }
);

Route::resource('tool', ToolController::class)
    ->only(['index']);
