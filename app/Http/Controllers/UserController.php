<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    public function viewProfile(int $id): JsonResponse
    {
        $userDTO = $this->userService->getUserProfile($id);

        return response()->json($userDTO);
    }
}

