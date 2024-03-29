<?php

namespace App\Exceptions;

use App\Http\Response\ResponseGenerator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application as ApplicationAlias;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): HttpResponse
    {
        $responseGenerator = new ResponseGenerator();

        return match (true) {
            $e instanceof UnauthorizedException => $responseGenerator->unauthorized($e->getMessage()),
            $e instanceof ValidationException => $responseGenerator->unprocessableEntity(['meta' => [], 'data' => $e->errors()]),
            $e instanceof UnprocessableEntityHttpException => $responseGenerator->unprocessableEntity(['meta' => [], 'data' => []], $e->getMessage()),
            $e instanceof AuthorizationException => $responseGenerator->forbidden(),
            $e instanceof NotFoundHttpException, $e instanceof ModelNotFoundException => $responseGenerator->notFound($e instanceof NotFoundHttpException ? $e->getMessage() : 'Model not found'),
            (config('app.env') != 'local') => $responseGenerator->serverError(),
            default => parent::render($request, $e),
        };
    }

    public function unauthenticated($request, AuthenticationException $exception): ApplicationAlias|Response|JsonResponse|RedirectResponse|Application|ResponseFactory
    {
        throw new UnauthorizedException();
    }

    public function shouldReturnJson($request, Throwable $e): bool
    {
        return true;
    }
}
