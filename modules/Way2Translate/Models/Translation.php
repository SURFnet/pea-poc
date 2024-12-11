<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Modules\Way2Translate\Helpers\Arr;

class Translation extends Model
{
    /** @var string */
    protected $table = 'way2translate_translations';

    /** @var array<int, string> */
    protected $fillable = [
        'namespace',
        'locale',
        'group',
        'prefix',
        'key',
        'value',
        'in_latest_import',
    ];

    public function scopeForContext(Builder $query, string $locale, string $namespace, string $group): void
    {
        $query->where('locale', $locale)
            ->where('namespace', $namespace)
            ->where('group', $group);
    }

    public static function getTranslatedLanguages(): array
    {
        return self::select('locale')->groupBy('locale')->pluck('locale')->toArray();
    }

    public static function missingTranslation(string $locale, string $namespace, string $group, string $name): bool
    {
        return self::forContext($locale, $namespace, $group)
            ->where('name', $name)->doesntExist();
    }

    public static function getGroupTranslations(string $locale, string $group, string $namespace): Collection
    {
        return self::forContext($locale, $namespace, $group)
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function getGroupsByNamespace(): SupportCollection
    {
        $groupsByNamespace = self::select('group', 'namespace')
            ->orderBy('namespace')
            ->orderBy('group')
            ->distinct()
            ->get()
            ->groupBy('namespace');

        return $groupsByNamespace;
    }

    public static function getGroupTranslationsForLoader(string $locale, string $group, string $namespace): array
    {
        return Cache::remember(
            self::getCacheKey($locale, $group, $namespace),
            Config::get('way2translate.cache-duration'),
            function () use ($group, $locale, $namespace) {
                $translations = self::getGroupTranslations($locale, $group, $namespace)
                    ->pluck('value', 'name')
                    ->toArray();

                return Arr::undot($translations);
            }
        );
    }

    public static function clearGroupCache(string $locale, string $group, string $namespace): void
    {
        Cache::forget(self::getCacheKey($locale, $group, $namespace));
    }

    public static function getCacheKey(string $locale, string $group, string $namespace): string
    {
        return 'way2translate.translations'
            . '.' . $locale
            . '.' . $namespace
            . '.' . $group;
    }

    public static function hasDefaultTranslations(): bool
    {
        return !self::where('locale', Config::get('way2translate.default-locale'))->doesntExist();
    }

    public static function getPercentCompleted(string $localeCode): float
    {
        $allTranslations = self::where('locale', $localeCode)->get();
        $emptyTranslations = $allTranslations->where('value', '');

        $percentEmpty = ($emptyTranslations->count() / $allTranslations->count()) * 100;
        $percentFilled = 100 - $percentEmpty;

        return round($percentFilled, 2);
    }
}
