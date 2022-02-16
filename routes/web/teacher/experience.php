<?php

declare(strict_types=1);

use App\Http\Controllers\Teacher\ExperienceController;
use Illuminate\Support\Facades\Route;

Route::resource('tool.experience', ExperienceController::class)
    ->only(['store', 'update', 'destroy'])
    ->shallow();
