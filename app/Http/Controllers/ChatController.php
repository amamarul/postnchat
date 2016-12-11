<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    private function setChatView($receiver_id)
    {
        $chat = new Chat();
//        $chat = $chat->all();
        $chat = $chat->where('receiver_id', Auth::user()->id)
            ->where('user_id', $receiver_id)->get();
        foreach ($chat as $c) {
            $c->view = true;
            $c->save();
        }
    }
    public function getChatMessages($receiver_id)
    {
        $this->setChatView($receiver_id);
        $chat = new Chat();
        $chat = $chat->where('receiver_id', $receiver_id)
            ->where('user_id', Auth::user()->id)
            ->orWhere('receiver_id', Auth::user()->id)
                ->where('user_id', $receiver_id)->get();
        return view('inc.chat', ['chat' => $chat, 'receiver_id' => $receiver_id]);
    }

    public function postSaveChatMsg(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        $chat = new Chat();
        $chat->receiver_id = $request['receiver_id'];
        $chat->user_id = Auth::user()->id;
        $chat->message = $request['message'];
        $chat->view = false;

        $chat->save();
        return redirect()->route('chat', ['receiver_id' => $chat->receiver_id], 301);
    }
}
