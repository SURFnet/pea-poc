<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'other',
        'as'         => 'other.',
        'middleware' => ['auth'],
    ],
    function (): void {
        foreach (File::files(__DIR__ . '/other') as $file) {
            require $file;
        }
    }
);
