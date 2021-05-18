<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(RegisterRequest $request)
    {
        $requestData = $request->only(['email', 'password']);
        $user = $this->userService->createUser($requestData);
        $token = $user->createToken('AuthToken')->accessToken;
        $response = ['token' => $token];
        return response($response, 201);

    }
}
