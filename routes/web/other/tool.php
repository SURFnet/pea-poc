<?php

declare(strict_types=1);

use App\Http\Controllers\Other\ToolController;
use Illuminate\Support\Facades\Route;

Route::resource('tool', ToolController::class)
    ->only(['index', 'show']);
