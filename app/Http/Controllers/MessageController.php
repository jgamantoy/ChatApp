<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\GroupChat;
use App\Models\GroupChatUser;
use App\Models\Message;

use App\Jobs\SendMessageEmail;

use Validator;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:1',
            'group_chat_id' => ['required', 'integer', 'exists:group_chats,id']
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $user = $request->user();
        $gc = GroupChat::find($request->group_chat_id);
        $userGC = GroupChatUser::where('user_id', $user->id)
                    ->where('group_chat_id', $gc->id)
                    ->firstOrFail();

        if ($userGC) {
            $content = \Purifier::clean($request->content);

            $content = preg_replace('/\r\n|\r|\n/', '<br />', $content);
            $content = str_replace('{group_name}', $gc->name, $content);
    
            $message = Message::create([
                'content' => $content,
                'render' => ' ',
                'group_chat_id' => 1,
                'user_id' => $request->user()->id
            ]);
    
            $gc->touch();

            SendMessageEmail::dispatch($user, $gc, $message);

            return response([
                'message' => $message,
                'group' => $gc
            ], 200);
        } else {
            return response('Unathorized', 403);
        }
    }

    // to do add email feature with $u param
    // make unreadable code with comment trust me
    // render
}
