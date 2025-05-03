<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

        $this->renderable(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 429);
            } else {
                return response($e->getMessage(), 429);
            }
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            $validator = $e->validator;
            $errors = $validator->errors();

            if ($request->is('api/*')) {
                $errors = $errors->toArray();
                foreach ($errors as $field => $messages) {
                    $errors[$field] = $messages[0];
                }
            }
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $errors
                ], 422);
            }
            return redirect()->back()->withInput()->withErrors($errors);
        });
    }
}
