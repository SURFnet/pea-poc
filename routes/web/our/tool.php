<?php

declare(strict_types=1);

use App\Http\Controllers\Our\ToolController;
use Illuminate\Support\Facades\Route;

Route::resource('tool', ToolController::class)
    ->only(['show']);
