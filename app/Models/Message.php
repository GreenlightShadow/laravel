<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'to_user',
        'from_user',
        'message',
    ];

    public function createMessage($userTo, $message){
        $this->to_user = $userTo->id;
        $this->from_user = Auth::id();
        $this->message = $message;
        $this->save();

        return true;
    }
}
