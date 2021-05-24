<?php

namespace App\Services;

use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;

class UserService
{
    public function createUser($data)
    {
        $user = new User();
        $user->fill($data);
        $user->save();

        return $user;
    }
    public function createResetRow($user_id):string
    {
        $reset = ResetPassword::where('user_id', $user_id)->first();

        if(!$reset){
            $reset = new ResetPassword;
        }

        $reset->user_id = $user_id;
        $reset->token = 'hrenkjfle3ilhnl43423gblb423';
        $reset->save();

        return $reset->token;
    }

    public function updatePassword($checkToken, $password): array
    {
        $current = Carbon::now();

        if ($checkToken->created_at->diffInHours($current) <= 2) {
            $user = User::where('id', $checkToken->user_id)->first();
            $password = bcrypt($password);
            $user->password = $password;
            $user->save();
            $checkToken->delete();
            $token = $user->createToken('AuthToken')->accessToken;

            return ['token' => $token];
        }

        return ['message' => 'Outdated token'];
    }
}
