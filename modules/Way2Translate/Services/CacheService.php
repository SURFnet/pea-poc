<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Services;

use Illuminate\Support\Facades\Log;
use Modules\Way2Translate\Models\Language;
use Modules\Way2Translate\Models\Locale;

class CacheService
{
    /** Checks the integrity of the translation cache. If there is an inconsistency, it will be purged. */
    public function enforceCacheIntegrity(): void
    {
        $cachedLocales = Locale::get();
        $activeLanguage = Language::getActive();

        $shouldHaveCache = $activeLanguage->isNotEmpty();
        $hasCache = $cachedLocales->isNotEmpty();

        if ($shouldHaveCache && !$hasCache) {
            Log::error('Way2Translate: the translation cache is empty when it should not have been');

            $this->refreshLocaleCache();
        }
    }

    private function refreshLocaleCache(): void
    {
        Locale::clearCache();

        Locale::get();
    }
}
