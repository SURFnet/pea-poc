<?php

declare(strict_types=1);

namespace App\Enums\InstituteTool;

use App\Enums\BaseEnum;

class DataClassification extends BaseEnum
{
    protected static string $translationKey = 'institute.tool.data_classifications.';

    public const PUBLIC = 'public';
    public const INTERNAL = 'internal';
    public const CONFIDENTIAL = 'confidential';
    public const SECRET = 'secret';
}
