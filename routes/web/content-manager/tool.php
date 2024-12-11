<?php

declare(strict_types=1);

use App\Http\Controllers\ContentManager\ToolController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'tool',
        'as'     => 'tool.',
    ],
    function (): void {
        Route::put('publish/{tool}', [ToolController::class, 'publish'])
            ->name('publish');

        Route::put('discard-concept/{tool}', [ToolController::class, 'discardConcept'])
            ->name('discard-concept');

        Route::put('publish-concept/{tool}', [ToolController::class, 'publishConcept'])
            ->name('publish-concept');

        Route::get('{tool}/log', [ToolController::class, 'log'])
            ->name('log');

        Route::post('{tool}/cancel-edit', [ToolController::class, 'cancelEdit'])
            ->name('cancel-edit');

        Route::post('{continue?}', [ToolController::class, 'store'])
            ->name('store');

        Route::put('{tool}/update/{continue?}', [ToolController::class, 'update'])
            ->name('update');
    }
);

Route::resource('tool', ToolController::class)
    ->except(['show', 'store', 'update', 'destroy']);
