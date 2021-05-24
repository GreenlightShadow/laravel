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
        $user = User::where('email', $email)->first();
        if ($user) {
            $resetToken = $this->userService->createResetRow($user->id);
            $response = ['token' => $resetToken, 'email' => $email];
            return response($response, 200);
        }
    }

    public function updatePassword(UpdateRequest $request)
    {
        $requestData = $request->only(['token', 'password']);
        $checkToken = ResetPassword::where('token', $requestData['token'])->first();
        if ($checkToken) {
            $response = $this->userService->updatePassword($checkToken, $requestData['password']);

            return response($response, 200);
        }
        return response('coudn\'t find token');
    }
}
