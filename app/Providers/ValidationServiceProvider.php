<?php

declare(strict_types=1);

namespace App\Providers;

use App\Rules\ChamberOfCommerceNumber;
use App\Rules\DbString;
use App\Rules\DbText;
use App\Rules\PostalCode;
use App\Rules\UriRule;
use App\Rules\VATNumber;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PostalCode::addToLaravelValidation();
        ChamberOfCommerceNumber::addToLaravelValidation();
        VATNumber::addToLaravelValidation();
        DbString::addToLaravelValidation();
        DbText::addToLaravelValidation();
        UriRule::addToLaravelValidation();
    }
}
