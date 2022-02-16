<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('account.login');
});

Route::group(
    [
        'prefix' => 'account',
        'as'     => 'account.',
    ],
    function (): void {
        Route::get('', [AccountController::class, 'login'])
            ->name('login');

        Route::post('login-as-super-admin', [AccountController::class, 'loginAsSuperAdmin'])
            ->name('login-as-super-admin');

        Route::post('logout', [AccountController::class, 'logout'])
            ->name('logout');
    }
);
