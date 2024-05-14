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
    public function index(Request $request, $group_chat_id)
    {
        return GroupChat::with(['messages.user'])->find($group_chat_id);
    }

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
                    ->first();

        if ($userGC) {
            $content = \Purifier::clean($request->content);

            $content = preg_replace('/\r\n|\r|\n/', '<br />', $content);
            $content = str_replace('{group_name}', $gc->name, $content);
            $content = str_replace('{signature}', '<strong>' . $user->name . '</strong>', $content);

            $render = [];

            if ($request->has('images')) {
                $render['type'] = 'images';
                $new_images = [];
                foreach ($request->images as $image) {
                    if (pathinfo($image, PATHINFO_EXTENSION)) {
                        $image = str_replace('.png', '.jpeg', $image);
                    }
                    $image = $image . "?w=600&h=600";
                    if (str_contains($image, '\/stickers\/')) {
                        $image = $image . '&tracker';
                        if (str_contains($image, '\/pekori\/')) {
                            if (preg_match('/\/pekori\/(.*?)\.(jpeg)/', $str, $match) == 1) {
                                $ciphering = "AES-128-CTR";
                                $options = 0;
                                $encryption_iv = '1234567891011121';
                                $encryption = openssl_encrypt('pekori', $ciphering,
                                                $match[1], $options, $encryption_iv);
                                $image = $image . $ecryption;
                            } else {
                                $image = $image . 'null';
                            }
                        } else if (str_contains($image, '\/user\/')) {
                            $ciphering = "AES-128-CTR";
                            $options = 0;
                            $encryption_iv = '1234567891011121';
                            $encryption = openssl_encrypt($user->slug, $ciphering,
                                            'usersticker', $options, $encryption_iv);
                            $image = $image . $ecryption;
                        } else {
                            $image = $image . 'null';
                        }
                    }
                    array_push($new_images, $image);
                }
                $render['images'] = $new_images;
            }
    
            $senderCode = "placeholder";
        
            if ($request->has('mentions')) {
                $mentions = $request->mentions;
                $render['type'] = 'mention';
                foreach($mentions as $mention) {
                    $user = User::where('slug', $mention)->first();
                    \Mail::send('email.mention',
                        [
                            'group_name' => $gc->name,
                            'content' => $msg->content
                        ], function ($message) use ($f) {
                            $message->to('jgamantoy@gmail.com')
                                ->subject('New Message Recieved!');
                        }
                    );
                }

                $render['mentions'] = $mentions;
            }

            $message = Message::create([
                'content' => $content,
                'render' => json_encode($render),
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
}
