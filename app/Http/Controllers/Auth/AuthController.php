<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RegisterMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterMailService $registerMailService,
    ) {}

    /**
     * POST /api/auth/register
     */
    public function register(Request $request): JsonResponse
    {
        $user = $this->registerMailService->register($request);

        return response()->json([
            'message' => 'Inscription réussie',
            'user'    => $user,
        ], 201);
    }

    // login() / logout() pourront être ajoutés plus tard
}
