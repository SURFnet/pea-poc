<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'home',
        'as'         => 'home.',
        'middleware' => ['auth'],
    ],
    function (): void {
        Route::get('', [HomeController::class, 'index'])
            ->name('index');
    }
);
