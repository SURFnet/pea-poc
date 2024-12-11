<?php

declare(strict_types=1);

namespace App\Enums\Tool;

use App\Enums\BaseEnum;

class Tabs extends BaseEnum
{
    protected static string $translationKey = 'custom-field.tab_types.';

    public const PRODUCT = 'product';
    public const TECHNICAL = 'technical';
    public const PRIVACY_AND_SECURITY = 'privacy_and_security';
    public const SUPPORT = 'support';
    public const EDUCATION = 'education';
}
