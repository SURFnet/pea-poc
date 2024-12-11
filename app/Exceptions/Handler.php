<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use ReflectionClass;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        NotFoundHttpException::class,
    ];

    /**
     * A list of the exception types that should be logged at info level.
     *
     * @var array<int,string>
     */
    protected $reportAsInfo = [
        AuthorizationException::class,
        TokenMismatchException::class,
        SurfConextException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int,string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    /**
     * Report or log an exception.
     *
     * @throws \Exception
     */
    public function report(Throwable $exception): void
    {
        if ($this->shouldReportAsInfo($exception)) {
            Log::info($exception->getMessage());

            return;
        }

        $this->handleSentry($exception);

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $response = parent::render($request, $exception);

        if ($this->shouldGoToInertiaErrorPage($request, $response)) {
            return Inertia::render('Error', [
                    'status' => $response->getStatusCode(),
                ])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        }

        $handlerName = $this->findCustomHandler($exception);
        if (!is_null($handlerName)) {
            return App::make($handlerName)->render($request, $exception);
        }

        if ($this->shouldShowErrors()) {
            return $response;
        }

        return response()->view('error', [
            'message' => trans('message.error.general'),
        ], 500);
    }

    protected function handleSentry(Throwable $exception): void
    {
        if (!$this->shouldReport($exception)) {
            return;
        }

        if (App::environment(config('constants.environment.development'))) {
            return;
        }

        if (!App::bound('sentry')) {
            return;
        }

        App::get('sentry')->captureException($exception);
    }

    protected function shouldReportAsInfo(Throwable $exception): bool
    {
        return !is_null(Arr::first($this->reportAsInfo, function ($type) use ($exception) {
            return $exception instanceof $type;
        }));
    }

    protected function shouldShowErrors(): bool
    {
        return in_array(config('app.env'), config('constants.environments.show-errors'));
    }

    private function findCustomHandler(Throwable $exception): ?string
    {
        $reflect = new ReflectionClass($exception);

        while ($reflect instanceof ReflectionClass) {
            $handler = 'App\\Exceptions\\CustomHandlers\\' . $reflect->getShortName();
            if (class_exists($handler)) {
                return $handler;
            }

            $reflect = $reflect->getParentClass();
        }

        return null;
    }

    /**
     * Determines if we should send the visitor to an Inertia error page.
     *
     * @param \Illuminate\Http\Request                                             $request
     * @param \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    public function shouldGoToInertiaErrorPage($request, $response)
    {
        // on development environments we want to see the actual error
        if ($this->shouldShowErrors()) {
            return false;
        }

        return $request->header('X-Inertia') && $response->getStatusCode() >= 400;
    }
}
