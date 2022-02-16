<?php

declare(strict_types=1);

namespace App\Helpers;

class Url
{
    public static function getSubdomain(string $root): ?string
    {
        $rootStripped = self::stripProtocol($root);
        $rootStripped = self::stripDomain($rootStripped);

        $subdomain = str_replace('.', '', $rootStripped);

        if (empty($subdomain)) {
            return null;
        }

        return $subdomain;
    }

    private static function stripProtocol(string $string): string
    {
        return str_replace(config('constants.general.protocol'), '', $string);
    }

    private static function stripDomain(string $string): string
    {
        return str_replace(config('constants.general.domain'), '', $string);
    }
}
