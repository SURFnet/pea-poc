<?php

declare(strict_types=1);

namespace App\Enums\Tool;

use App\Enums\BaseEnum;

class StoredData extends BaseEnum
{
    protected static string $translationKey = 'tool.stored_data.';

    public const PERSONAL_DATA = 'personal_data';
    public const USAGE_LOGGING = 'usage_logging';
    public const OTHER = 'other';
}
