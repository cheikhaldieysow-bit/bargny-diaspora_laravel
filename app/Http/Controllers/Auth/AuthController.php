<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterMailService;
use App\Http\Requests\Auth\RegisterWithRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterMailService $registerMailService,
    ) {}

    /**
     * POST /api/auth/register
     */
    public function register(RegisterWithRequest $request): JsonResponse
    {
        $userDTO = $this->registerMailService->register(
            $request->validated()
        );

        return response()->json([
            'message' => 'Inscription réussie',
            'data'    => $userDTO,
        ], 201);
    }

    // login() / logout() pourront être ajoutés plus tard
}
