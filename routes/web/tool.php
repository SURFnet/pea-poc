<?php

declare(strict_types=1);

use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'tool',
    'as'         => 'tool.',
    'middleware' => ['auth'],
], function (): void {
    Route::get('{tags?}', [ToolController::class, 'index'])
        ->name('index')
        ->where('tags', '.*');

    Route::post('{tool}/change-following-status', [ToolController::class, 'changeFollowingStatus'])
        ->name('change-following-status');
});
