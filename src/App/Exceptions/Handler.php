<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function prepareJsonResponse($request, Throwable $e): JsonResponse
    {
        return new JsonResponse(
            ['message' => $e->getMessage()],
            $e instanceof HttpExceptionInterface ? $e->getStatusCode() : $this->getStatusCode($e),
            $e instanceof HttpExceptionInterface ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    protected function getStatusCode(Throwable $e): int
    {
        return $e->getCode() < Response::HTTP_CONTINUE || $e->getCode() > Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED
            ? Response::HTTP_INTERNAL_SERVER_ERROR
            : $e->getCode();
    }
}
