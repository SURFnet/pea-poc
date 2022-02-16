<?php

declare(strict_types=1);

namespace App\Enums\Tool;

use App\Enums\BaseEnum;

class AuthenticationMethod extends BaseEnum
{
    protected static string $translationKey = 'tool.authentication_methods.';

    public const SSO = 'sso';
    public const SURFCONEXT = 'surfconext';
    public const OIDC = 'oidc';
}
