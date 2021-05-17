<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(RegisterRequest $request){
        $requestData = $request->only(['email', 'password']);
        $requestData['password'] = bcrypt($requestData['password']);
        $user = new User();
        $user->fill($requestData);
        $user->save();
        $token = $user->createToken('AuthToken')->accessToken;
        $response = ['token' => $token];
        return response($response, 201);

    }
}
