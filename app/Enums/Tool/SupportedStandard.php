<?php

declare(strict_types=1);

namespace App\Enums\Tool;

use App\Enums\BaseEnum;

class SupportedStandard extends BaseEnum
{
    protected static string $translationKey = 'tool.supported_standards.';

    public const LIS = 'lis';
    public const QTI = 'qti';
    public const OOAPI = 'ooapi';
    public const EDUAPI = 'eduapi';
    public const LTI = 'lti';
    public const SAML = 'saml';
    public const XZAPI = 'xzapi';
    public const CALIPER = 'caliper';
}
