<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Events\UserOnlined;


class ChatController extends Controller
{
    //
    public function chat(){
        $users = User::where('id','<>', Auth::user()->id)->get();
        return view('chat.chatpublic', compact('users'));
    }

    public function sendMessage(Request $request){
        broadcast(new UserOnlined($request->user(), $request->message));
        return json_encode([
            'success' => 'done',
        ]);
    }
}
