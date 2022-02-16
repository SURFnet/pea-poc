<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'teacher',
        'as'         => 'teacher.',
        'middleware' => ['auth'],
    ],
    function (): void {
        foreach (File::files(__DIR__ . '/teacher') as $file) {
            require $file;
        }
    }
);
