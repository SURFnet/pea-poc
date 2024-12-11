<?php

declare(strict_types=1);

namespace App\Enums\Tags;

use App\Enums\BaseEnum;
use Illuminate\Support\Arr;

class TagTypes extends BaseEnum
{
    protected static string $translationKey = 'tag.tag_types.';

    // Tag types are ordered for frontend!
    public const CATEGORIES = 'categories';
    public const FEATURES = 'features';
    public const WORKING_METHODS = 'working_methods';
    public const DEVICES = 'devices';
    public const SOFTWARE_TYPES = 'software_types';
    public const STANDARDS = 'standards';
    public const OPERATING_SYSTEMS = 'operating_systems';
    public const DATA_PROCESSING_LOCATIONS = 'data_processing_locations';
    public const CERTIFICATIONS = 'certifications';
    public const TARGET_GROUPS = 'target_groups';
    public const COMPLEXITY = 'complexity';

    public static function forInstituteTool(): array
    {
        return [self::CATEGORIES];
    }

    public static function forTool(): array
    {
        return array_diff(self::toArray(), self::forInstituteTool());
    }

    public static function getTagsTypesAsSelectExcept(array $hideTypes): array
    {
        return Arr::except(self::asSelect(), $hideTypes);
    }

    public static function getTagsTypesToArrayExcept(array $hideTypes): array
    {
        return Arr::except(self::toArray(), $hideTypes);
    }

    public static function forTeacher(): array
    {
        return [
            self::CATEGORIES,
            self::FEATURES,
            self::WORKING_METHODS,
            self::DEVICES,
        ];
    }
}
