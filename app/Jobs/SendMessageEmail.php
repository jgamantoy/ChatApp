<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\GroupChat;
use App\Models\GroupChatUser;
use App\Models\Message;
use App\Models\User;

class SendMessageEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $u;
    private $gc;
    private $msg;

    public function __construct(User $u, GroupChat $gc, Message $msg)
    {
        $this->u = $u;
        $this->gc = $gc;
        $this->msg = $msg;
    }

    public function handle(): void
    {
        $u = $this->u;
        $gc = $this->gc;
        $msg = $this->msg;

        $followers = GroupChatUser::where('group_chat_id', $gc->id)
            ->whereNot('user_id', $u->id)
            ->with('user')
            ->get();

        foreach($followers as $f) {
            \Mail::send('email.new_message_email',
                [
                    'group_name' => $gc->name,
                    'content' => $msg->content
                ], function ($message) use ($f) {
                    $message->to('jgamantoy@gmail.com')
                        ->subject('New Message Recieved!');
                }
            );
        }
    }
}
