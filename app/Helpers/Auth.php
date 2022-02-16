<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth as VendorAuth;

class Auth
{
    public static function user(): ?User
    {
        return VendorAuth::user();
    }

    public static function check(): bool
    {
        return VendorAuth::check();
    }

    public static function logout(): void
    {
        VendorAuth::logout();
    }
}
