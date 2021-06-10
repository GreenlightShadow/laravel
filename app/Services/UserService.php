<?php

namespace App\Services;

use App\Mail\DeleteMail;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

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

        if(!$reset) {
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
    public function updateUser(int $id, array $data):object
    {
        $user = User::where('id', $id)->first();
        $user->fill($data);
        $user->save();

        return $user;
    }
    public function deleteUser(User $user):bool
    {
        $user->status = User::DISABLED;
        $user->update();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>You have been deleted yourself</h1>');
        Mail::to($user->email)->send(new DeleteMail($pdf));

        return true;
    }
}
