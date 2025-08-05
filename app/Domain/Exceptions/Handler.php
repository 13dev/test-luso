<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
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
        $this->reportable(function (ExternalOrderApiRequestFailedException $e) {
            logger()?->error('[Order API] Request failed', $e->context());
        });
    }


    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ExternalOrderApiRequestFailedException) {
            return response()->json([
                'message' => 'There was a problem processing your order.',
                'error' => $exception->getMessage(),
                'details' => app()->isProduction() ? null : $exception->context(),
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
