<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\CustomToolInformationController;
use Illuminate\Support\Facades\Route;

/**
 * @routePrefix("information-manager.")
 */
Route::group(
    [
        'prefix' => 'custom-tool/{tool}/information',
        'as'     => 'custom-tool.information.',
    ],
    function (): void {
        // Institute Tool

        Route::put('/', [CustomToolInformationController::class, 'update'])
            ->name('update');

        Route::get('edit', [CustomToolInformationController::class, 'edit'])
            ->name('edit');

        Route::put('publish', [CustomToolInformationController::class, 'publish'])
            ->name('publish');

        Route::put('unpublish', [CustomToolInformationController::class, 'unpublish'])
            ->name('unpublish');

        Route::put('publish-concept', [CustomToolInformationController::class, 'publishConcept'])
           ->name('publish-concept');

        Route::put('discard-concept', [CustomToolInformationController::class, 'discardConcept'])
           ->name('discard-concept');

        Route::post('cancel-edit', [CustomToolInformationController::class, 'cancelEdit'])
            ->name('cancel-edit');
    }
);
