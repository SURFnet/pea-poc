<?php

declare(strict_types=1);

use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'about',
        'as'         => 'about.',
        'middleware' => ['auth'],
    ],
    function (): void {
        Route::get('', [AboutController::class, 'index'])
            ->name('index');
    }
);
