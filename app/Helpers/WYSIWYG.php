<?php

declare(strict_types=1);

namespace App\Helpers;

class WYSIWYG
{
    public static function isEmpty(?string $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        return trim(strip_tags($value)) === '';
    }

    public static function valueForFrontend(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return self::isEmpty($value) ? null : $value;
    }
}
