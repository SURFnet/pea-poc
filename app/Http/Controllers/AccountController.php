<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Institute\StopImpersonatingAction;
use App\Enums\Auth\Role;
use App\Helpers\LoginRedirect;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    private array $supportedTestRoles = [
        'admin',
        Role::CONTENT_MANAGER,
        Role::INFORMATION_MANAGER,
        Role::TEACHER,
    ];

    public function login(): Response
    {
        return Inertia::render('account/Login', [
            'allowTestUserLogin' => $this->allowTestUserLogin(),
            'supportedTestRoles' => $this->supportedTestRoles,
        ]);
    }

    public function loginAsTestUser(Request $request): RedirectResponse
    {
        if (!$this->allowTestUserLogin()) {
            abort(403);
        }

        $validated = $this->validate($request, [
            'role' => ['required', Rule::in($this->supportedTestRoles)],
        ]);

        $role = $validated['role'];

        $user = User::where('name', "PAQT $role")->firstOrFail();

        Auth::login($user);

        (new StopImpersonatingAction())->execute(Auth::user());

        return LoginRedirect::doRedirect();
    }

    public function logout(): RedirectResponse
    {
        $user = Auth::user();
        if ($user->isImpersonating()) {
            (new StopImpersonatingAction())->execute($user);

            return redirect()->route('home.index');
        }

        Auth::logout();

        return redirect()->route('account.login');
    }

    private function allowTestUserLogin(): bool
    {
        return App::environment(array_merge(
            config('constants.environment.development'),
            config('constants.environment.staging'),
        ));
    }
}
