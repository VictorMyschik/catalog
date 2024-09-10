<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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

    public function render($request, \Throwable $e)
    {
        if ($e instanceof ExceptionAPI) {
            Log::error($e->getMessage(), [
                    'trace'   => array_slice($e->getTrace(), 0, 5),
                    'file'    => $e->getFile(),
                    'request' => $request->all(),
                ]
            );
            return $this->failResult($e->getMessage());
        }

        return parent::render($request, $e);
    }

    private function failResult(string $message): JsonResponse
    {
        return response()->json(['result' => false, 'error' => $message], 400);
    }
}
