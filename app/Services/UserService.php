<?php

namespace App\Services;

use App\Models\ResetPassword;
use App\Models\User;

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
    public function updatePassword($checkToken, $password):object
    {
        $user = User::where('id', $checkToken->user_id)->first();
        $password = bcrypt($password);
        $user->password = $password;
        $user->save();

        return $user;
    }
}
