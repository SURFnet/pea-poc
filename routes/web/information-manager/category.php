<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\CategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('category', CategoryController::class)
    ->except(['show']);
