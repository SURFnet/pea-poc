<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\InstitutesController;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'institutes',
        'as'     => 'institutes.',
    ],
    function (): void {
        Route::get('', [InstitutesController::class, 'index'])
            ->name('index');

        Route::post('impersonate/{institute}', [InstitutesController::class, 'impersonate'])
            ->name('impersonate');
    }
);
