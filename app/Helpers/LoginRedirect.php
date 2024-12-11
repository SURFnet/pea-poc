<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class LoginRedirect
{
    public static function doRedirect(): RedirectResponse
    {
        $user = Auth::user();
        if (empty($user)) {
            throw new AuthenticationException();
        }

        $route = config('constants.route.redirect_authenticated');

        if (!Route::has($route)) {
            throw new RouteNotFoundException('Default route specified in config does not exist.');
        }

        $url = LaravelLocalization::localizeUrl(route($route), $user->language);

        return redirect($url);
    }
}
