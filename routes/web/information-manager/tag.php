<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\TagController;
use Illuminate\Support\Facades\Route;

Route::resource('tag', TagController::class)
    ->except(['show']);
