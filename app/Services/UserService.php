<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser($data):object
    {
        $user = new User();
        $user->fill($data);
        $user->save();

        return $user;
    }
}
