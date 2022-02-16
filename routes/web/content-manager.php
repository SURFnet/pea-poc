<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'content-manager',
        'as'         => 'content-manager.',
        'middleware' => ['auth'],
    ],
    function (): void {
        foreach (File::files(__DIR__ . '/content-manager') as $file) {
            require $file;
        }
    }
);
