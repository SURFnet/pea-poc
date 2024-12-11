<?php

declare(strict_types=1);

namespace App\Enums\InstituteTool;

use App\Enums\BaseEnum;

class Status extends BaseEnum
{
    protected static string $translationKey = 'institute.tool.statuses.';

    protected static array $privateValues = [self::UNRATED, self::UNPUBLISHED];

    public const UNRATED = 'unrated';
    public const UNPUBLISHED = 'unpublished';

    public const ALLOWED = 'allowed';
    public const ALLOWED_UNDER_CONDITIONS = 'allowed_under_conditions';
    public const DISALLOWED = 'disallowed';

    public static function toArray(bool $excludePrivateValues = true): array
    {
        if (!$excludePrivateValues) {
            return parent::toArray();
        }

        return array_diff(parent::toArray(), self::$privateValues);
    }

    public static function asFilterSelect(): array
    {
        $options = [];

        foreach (static::toArray(false) as $option) {
            $options[$option] = trans(static::$translationKey . $option);
        }

        return $options;
    }

    public static function customOrder(): array
    {
        return [
            self::ALLOWED,
            self::ALLOWED_UNDER_CONDITIONS,
            self::DISALLOWED,
        ];
    }

    public static function forLegend(): array
    {
        return [
            self::ALLOWED,
            self::ALLOWED_UNDER_CONDITIONS,
            self::DISALLOWED,
            self::UNRATED,
        ];
    }
}
