<?php

declare(strict_types=1);

namespace App\Enums\Activity;

use App\Enums\BaseEnum;

class Event extends BaseEnum
{
    public const CREATED = 'created';
    public const UPDATED = 'updated';
    public const DELETED = 'deleted';
    public const RESTORED = 'restored';
}
