<?php

declare(strict_types=1);

use App\External\Auth\SURF\Interfaces\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'auth/surf',
    'as'         => 'auth.surf.',
    'middleware' => ['web', 'guest'],
], function (): void {
    Route::get('', [AuthController::class, 'login'])->name('login');
    Route::get('callback', [AuthController::class, 'callback'])->name('callback');
});
