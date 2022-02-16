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
    }
);

Route::resource('tool', ToolController::class)
    ->except(['show', 'destroy']);
