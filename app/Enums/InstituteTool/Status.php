<?php

declare(strict_types=1);

namespace App\Enums\InstituteTool;

use App\Enums\BaseEnum;

class Status extends BaseEnum
{
    protected static string $translationKey = 'institute.tool.statuses.';

    protected static array $privateValues = [self::PROHIBITED, self::UNRATED, self::UNPUBLISHED];

    public const RECOMMENDED = 'recommended';
    public const SUPPORTED = 'supported';
    public const FREE_TO_USE = 'free_to_use';
    public const NOT_RECOMMENDED = 'not_recommended';
    public const PROHIBITED = 'prohibited';
    public const UNRATED = 'unrated';
    public const UNPUBLISHED = 'unpublished';

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
            self::RECOMMENDED,
            self::SUPPORTED,
            self::FREE_TO_USE,
            self::NOT_RECOMMENDED,
            self::PROHIBITED,
        ];
    }

    public static function forLegend(): array
    {
        return [
            self::RECOMMENDED,
            self::SUPPORTED,
            self::FREE_TO_USE,
            self::NOT_RECOMMENDED,
            self::PROHIBITED,
            self::UNRATED,
        ];
    }
}
