<?php

declare(strict_types=1);

namespace App\Enums\Auth;

use App\Enums\BaseEnum;

class Role extends BaseEnum
{
    public const TEACHER = 'teacher';
    public const INFORMATION_MANAGER = 'information_manager';
    public const CONTENT_MANAGER = 'content_manager';
}
