<?php

declare(strict_types=1);

namespace App\Enums\InstituteTool;

use App\Enums\BaseEnum;

class Sort extends BaseEnum
{
    protected static string $translationKey = 'institute.tool.sort.';

    const UPDATED_AT_ASC = 'updated_at';
    const UPDATED_AT_DESC = '-updated_at';
}
