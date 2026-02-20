<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\Auth\EmailAlreadyUsedException;
use App\Exceptions\Auth\PhoneAlreadyUsedException;
use App\Exceptions\Auth\RoleNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
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
        // Exceptions personnalisées
        $this->renderable(function (EmailAlreadyUsedException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT); // 409
        });

        $this->renderable(function (PhoneAlreadyUsedException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_CONFLICT); // 409
        });

        $this->renderable(function (RoleNotFoundException $e, $request) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND); // 404
        });

        // Validation standard
        $this->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors'  => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
        });
    }
}
