<?php

declare(strict_types=1);

namespace App\Exceptions\CustomHandlers;

use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

class AuthorizationException extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => trans('message.error.api.unauthorized')], 403);
        }

        return response()->view('error', [
            'message' => trans('message.error.unauthorized'),
        ], 403);
    }
}
