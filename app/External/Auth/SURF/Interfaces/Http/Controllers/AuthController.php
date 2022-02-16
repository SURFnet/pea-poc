<?php

declare(strict_types=1);

namespace App\External\Auth\SURF\Interfaces\Http\Controllers;

use App\External\Auth\Enums\SocialiteDriver;
use App\External\Auth\SURF\Manager;
use App\Helpers\LoginRedirect;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\SURFconext\Provider;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
    public function login(): RedirectResponse
    {
        /** @var Provider */
        $driver = Socialite::driver(SocialiteDriver::SURF);

        return $driver->stateless()->redirect();
    }

    public function callback(Manager $manager): RedirectResponse
    {
        /** @var Provider */
        $driver = Socialite::driver(SocialiteDriver::SURF);

        $externalUser = $driver->stateless()->user();

        $user = $manager->createOrUpdateUser($externalUser);

        Auth::login($user);

        return LoginRedirect::doRedirect();
    }
}
