<?php

declare(strict_types=1);

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'locale',
        'as'         => 'locale.',
        'middleware' => ['auth'],
    ],
    function (): void {
        Route::get('set/{locale}', [LocaleController::class, 'set'])
            ->name('set');
    }
);
