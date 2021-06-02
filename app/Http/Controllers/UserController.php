<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

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
        $requestData['password'] = bcrypt($requestData['password']);
        $user = $this->userService->createUser($requestData);
        $token = $user->createToken('AuthToken')->accessToken;
        $response = ['token' => $token];

        return response($response, 201);
    }

    public function login(LoginRequest $request)
    {
        $requestData = $request->only(['email', 'password']);

        if(!Auth::attempt($requestData)) {

            return response(['message' => 'Bad data'], 401);
        }

        $token = Auth::user()->createToken('AuthToken')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = User::where('id', $request->id)->first();

        if (!$user) {
            return response('error', 404);
        }
        if (Auth::user()->can('update', $user)) {
            $user = $this->userService->updateUser($request->id, $request->validated());
        } else {
            return response('Data can be updated only by account owner', 403);
        }
        $token = $user->createToken('AuthToken')->accessToken;
        $response = ['token' => $token];

        return response($response, 200);
    }

    public function getUsers()
    {
        $users = User::all();
        $email = $users->pluck('email');
        $response = ['users' => $email];

        return response($response, 200);
    }

    public function getUserData()
    {
        $user = User::findOrFail(request()->route('id'));
        if($user) {
            if (Auth::user()->can('view', $user)) {
                $response = ['user' => UserResource::collection(User::all())];
                return response($response, 200);
            } else {
                return response('You are not owner of this data', 403);
            }
        } else {
            return response('Not Found', 404);
        }
    }
}
