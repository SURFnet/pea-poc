<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\InstituteTool\Status;
use App\Helpers\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function rootView(Request $request): ?string
    {
        return 'layouts.admin.app';
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function version(Request $request): ?string
    {
        if (App::environment(config('constants.environment.testing'))) {
            return null;
        }

        return md5_file(public_path('dist/admin/mix-manifest.json'));
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app' => [
                'name'  => config('app.name'),
                'isDev' => App::environment(config('constants.environment.development')),
            ],
            'flashNotifications' => function () {
                return Session::get('flash_notification');
            },
            'currentUser' => function () {
                if (!Auth::check()) {
                    return;
                }

                return new UserResource(Auth::user());
            },
            'currentRouteName' => function () {
                return Route::currentRouteName();
            },
            'instituteTool' => [
                'legendStatuses' => Status::forLegend(),
            ],
        ]);
    }
}
