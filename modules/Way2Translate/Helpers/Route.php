<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Helpers;

use Illuminate\Support\Facades\Cache;

class Route
{
    public static function clearCache(): void
    {
        Cache::forget(self::getCacheKey());
    }

    public static function getCacheKey(): string
    {
        return 'way2translate.lang';
    }
}
