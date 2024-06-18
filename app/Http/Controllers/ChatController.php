<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;


class ChatController extends Controller
{
    //
    public function chat(){
        $users = User::where('id','<>', Auth::user()->id)->get();
        return view('chat.chatpublic', compact('users'));
    }
}
