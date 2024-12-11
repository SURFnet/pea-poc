<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Locale
{
    public static function getLocalizedFieldValue(Model $model, string $fieldName): ?string
    {
        $localizedAttribute = self::getValueWithLocaleSuffix($fieldName);

        $value = $model->{$localizedAttribute};

        if (!empty($value)) {
            return $value;
        }

        return $model->{$fieldName . '_en'};
    }

    public static function getLocalizedTranslation(string $key): ?string
    {
        $localizedKey = self::getValueWithLocaleSuffix($key);

        return trans($localizedKey);
    }

    private static function getValueWithLocaleSuffix(string $value): string
    {
        return $value . '_' . App::getLocale();
    }
}
