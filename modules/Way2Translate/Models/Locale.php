<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Locale
{
    public static function get(): Collection
    {
        return Cache::remember(
            self::getCacheKey(),
            Config::get('way2translate.cache-duration'),
            function () {
                $activeLanguages = Language::getActive();
                $translatedLanguages = Translation::getTranslatedLanguages();

                $allLocales = [];
                foreach (Config::get('way2translate.locales') as $code => $locale) {
                    $locale['code'] = $code;
                    $locale['active'] = false;
                    $locale['is_translated'] = false;
                    $locale['translated_percent'] = 0;

                    if (!empty($activeLanguages->where('locale', $code)->toArray())) {
                        $locale['active'] = true;
                    }

                    if (in_array($code, $translatedLanguages)) {
                        $locale['is_translated'] = true;
                        $locale['translated_percent'] = Translation::getPercentCompleted($code);
                    }

                    $allLocales[] = $locale;
                }

                return collect($allLocales)->sortBy('name');
            }
        );
    }

    public static function clearCache(): void
    {
        Cache::forget(self::getCacheKey());
    }

    public static function getCacheKey(): string
    {
        return 'way2translate.active.locales';
    }

    public static function getByCode(string $code): array
    {
        $allLocales = self::get();

        return $allLocales->where('code', $code)->first();
    }

    public static function getActive(): Collection
    {
        $allLocales = self::get();

        return $allLocales->where('active', true);
    }

    public static function getDefaultLanguageCode(): string
    {
        if (Config::get('way2translate.default-locale')) {
            return Config::get('way2translate.default-locale');
        }

        return self::getActive()->first()['code'];
    }

    public static function getTranslated(): Collection
    {
        $allLocales = self::get();

        return $allLocales->where('is_translated', true);
    }

    public static function getNonTranslated(): Collection
    {
        $allLocales = self::get();

        return $allLocales->where('is_translated', false);
    }
}
