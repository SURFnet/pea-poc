<?php

declare(strict_types=1);

namespace App\Enums\Tool;

use App\Enums\BaseEnum;

class Status extends BaseEnum
{
    protected static string $translationKey = 'tool.statuses.';

    public const CONCEPT = 'concept';
    public const PUBLISHED = 'published';
}
