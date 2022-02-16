<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'information-manager',
        'as'         => 'information-manager.',
        'middleware' => ['auth'],
    ],
    function (): void {
        foreach (File::files(__DIR__ . '/information-manager') as $file) {
            require $file;
        }
    }
);
