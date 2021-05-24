<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\ResetPassword;
use App\Services\UserService;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetMail;

class PasswordResetController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function resetPassword(ResetRequest $request)
    {
        $email = $request->only('email');
        $checkEmail = User::where('email', $email)->first();
        if ($checkEmail)
        {
            $resetToken = $this->userService->createResetRow($checkEmail->id);
            Mail::to($email)->send(new ResetMail($resetToken, $email));

            return new ResetMail($resetToken, $email);
        } else return 'Not Found Email';
    }

    public function updatePassword(UpdateRequest $request)
    {
        $requestData = $request->only(['token', 'password']);
        $checkToken = ResetPassword::where('token', $requestData['token'])->first();
        $current = Carbon::now();
        $check = DATE_FORMAT($checkToken->created_at, 'Y-m-d H:i:s');
            if($checkToken)
            {
                if($current -> diffInHours($check) < 2 )
                {
                    $user = $this->userService->updatePassword($checkToken, $requestData['password']);
                    $token = $user->createToken('AuthToken')->accessToken;
                    $response = ['token' => $token];
                    $checkToken->delete();
                }
                else
                {
                    $checkToken->delete();

                    return response('Token out of date');
                }
                return response($response, 201);
            }
    }
}
