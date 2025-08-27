<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            $status = 500;

            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            }

            return response()->json([
                'error' => $exception->getMessage(),
                'code' => $status
            ], $status);
        }

        return parent::render($request, $exception);
    }
}
