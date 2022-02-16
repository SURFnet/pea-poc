<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\LoginRedirect;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function login(): Response
    {
        return Inertia::render('account/Login', [
            'canLoginAsSuperAdmin' => $this->allowSuperAdminLogin(),
        ]);
    }

    public function loginAsSuperAdmin(): RedirectResponse
    {
        if (!$this->allowSuperAdminLogin()) {
            abort(403);
        }

        Auth::login(User::where('name', 'PAQT Admin')->firstOrFail());

        return LoginRedirect::doRedirect();
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('account.login');
    }

    private function allowSuperAdminLogin(): bool
    {
        return App::environment(array_merge(
            config('constants.environment.development'),
            config('constants.environment.staging'),
        ));
    }
}
