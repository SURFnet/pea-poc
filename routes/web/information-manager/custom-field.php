<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\CustomFieldController;
use Illuminate\Support\Facades\Route;

Route::resource('custom-field', CustomFieldController::class)
    ->except(['show']);
