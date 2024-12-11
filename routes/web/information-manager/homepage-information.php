<?php

declare(strict_types=1);

use App\Http\Controllers\InformationManager\HomepageInformationController;

Route::singleton('homepage-information', HomepageInformationController::class)
    ->only(['edit', 'update']);
