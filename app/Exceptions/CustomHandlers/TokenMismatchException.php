<?php

declare(strict_types=1);

namespace App\Exceptions\CustomHandlers;

use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

class TokenMismatchException extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => trans('message.error.api.csrf-failed')], 419);
        }

        flash(trans('message.error.inactivity'), 'danger');

        return redirect()->route(config('constants.route.redirect_unauthenticated'));
    }
}
