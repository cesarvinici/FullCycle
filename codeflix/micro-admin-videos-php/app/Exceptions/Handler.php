<?php

namespace App\Exceptions;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Exception\NotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
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

        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof EntityValidationException) {
            return response()->json([
                'message' => $exception->getMessage(),
                'errors' => $exception->getErrors()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}
