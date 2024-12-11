<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'admin',
        'as'         => 'admin.',
        'middleware' => ['auth'],
    ],
    function (): void {
        foreach (File::files(__DIR__ . '/admin') as $file) {
            require $file;
        }
    }
);
