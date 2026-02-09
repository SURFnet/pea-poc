<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Dto\PiwikDataLayerDto;
use Illuminate\Support\Facades\Config;

class PiwikHelper
{
    public static function getPiwikDatalayer(): array
    {
        if (!Config::get('constants.piwik_key')) {
            return [];
        }

        if (!Auth::check()) {
            return [];
        }

        $user = Auth::user();

        return (new PiwikDataLayerDto($user->institute->short_name, $user->roles))->jsonSerialize();
    }
}
