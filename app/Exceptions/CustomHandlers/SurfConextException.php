<?php

declare(strict_types=1);

namespace App\Exceptions\CustomHandlers;

use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

class SurfConextException extends Handler
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
            return response()->json(['error' => $exception->getMessage()], 403);
        }

        return response()->view('error', [
            'message' => trans('message.error.unauthorized'),
            'reason'  => $exception->getMessage(),
        ], 403);
    }
}
