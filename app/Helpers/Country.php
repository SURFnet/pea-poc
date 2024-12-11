<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Monarobase\CountryList\CountryListFacade;

class Country
{
    public static function getAsSelect(bool $emptyOption = true): array
    {
        $options = CountryListFacade::getList(App::getLocale(), 'php');

        if ($emptyOption) {
            $options = Arr::prepend($options, null, '-');
        }

        return $options;
    }

    public static function getCodes(): array
    {
        $countries = self::getAsSelect(false);

        return array_keys($countries);
    }

    public static function getName(string $code): string
    {
        return CountryListFacade::getOne($code, App::getLocale());
    }
}
