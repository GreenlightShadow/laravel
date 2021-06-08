<?php

namespace App\Services;

use App\Models\Message;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function createMessage(object $userTo, object $userFrom, string $text):object
    {
        $message = new Message();
        $message->to_user = $userTo->id;
        $message->from_user = $userFrom->id;
        $message->message = $text;
        $message->save();

        return $message;
    }
}
