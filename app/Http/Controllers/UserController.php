<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\DeleteMail;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


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
        $users = User::where('status', User::ENABLED)->get();
        $email = $users->pluck('email');
        $response = ['users' => $email];

        return response($response, 200);
    }

    public function getUserData(Request $request, $userId)
    {
        $user = User::find($userId);
        if($user) {
            if ($request->user()->can('view', $user)) {
                $response = ['user' => UserResource::collection(User::where('id', $userId)->get())];
                return response($response, 200);
            } else {
                return response('You are not owner of this data', 403);
            }
        } else {
            return response('Not Found', 404);
        }
    }
    public function deleteUser(Request $request, $userId)
    {
        $user = User::find($userId);
        if ($user) {
            if ($request->user()->can('delete', $user)) {
                $this->userService->deleteUser($user);
                return response('Deletion success', 200);
            } else {
                return response('You are not this user', 403);
            }
        } else {
            return response('Not Found', 404);
        }
    }
}
