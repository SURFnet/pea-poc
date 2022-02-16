<?php

declare(strict_types=1);

namespace App\Exceptions\CustomHandlers;

use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

class ValidationException extends Handler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable                $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
