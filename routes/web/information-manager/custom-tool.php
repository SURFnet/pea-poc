<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\CustomToolController;
use Illuminate\Support\Facades\Route;

/**
 * @routePrefix("information-manager.")
 */
Route::group(
    [
        'prefix' => 'custom-tool',
        'as'     => 'custom-tool.',
    ],
    function (): void {
        Route::group(['prefix' => '{tool}'], function (): void {
            Route::put('update/{continue?}', [CustomToolController::class, 'update'])
                ->name('update');

            Route::put('publish', [CustomToolController::class, 'publish'])
                ->name('publish');

            Route::put('publish-concept', [CustomToolController::class, 'publishConcept'])
                ->name('publish-concept');

            Route::put('discard-concept', [CustomToolController::class, 'discardConcept'])
                ->name('discard-concept');

            Route::post('cancel-edit', [CustomToolController::class, 'cancelEdit'])
                ->name('cancel-edit');

            Route::get('log', [CustomToolController::class, 'log'])
                ->name('log');
        });

        Route::post('{continue?}', [CustomToolController::class, 'store'])
            ->name('store');
    }
);

Route::resource('custom-tool', CustomToolController::class)
    ->parameter('custom-tool', 'tool')
    ->only(['index', 'create', 'edit', 'destroy']);
