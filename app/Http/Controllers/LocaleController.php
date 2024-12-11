<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\LoginRedirect;
use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Way2Translate\Models\Locale;

class LocaleController extends Controller
{
    public function set(string $locale): RedirectResponse
    {
        $originalUrl = url()->previous();

        if (empty($originalUrl) || !Locale::getActive()->pluck('code')->contains($locale)) {
            return LoginRedirect::doRedirect();
        }

        $user = Auth::user();
        $user->language = $locale;
        $user->update();

        $localizedUrl = LaravelLocalization::localizeUrl($originalUrl, $locale);

        return redirect($localizedUrl);
    }
}
