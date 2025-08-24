<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\Events\MessageSent;

class ChatRoom extends Component
{
    public $message = '';
    public $dealing_id;
    public $messages = [];

    protected $rules = [
        'message' => 'required|max:400'
    ];

    public function mount($dealing_id)
    {
        $this->dealing_id = $dealing_id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::where('dealing_id', $this->dealing_id)
            ->with('user')
            ->get();
    }

    public function sendMessage()
    {
        $thsi->validate();

        $message = ChatMessage::create([
            'dealing_id' => $this->dealing_id,
            'user_id' => auth()->id(),
            'message' => $this->message,
//            'image' => $image_path,
        ]);

        $this->message = '';

        // ブロードキャストイベントの発火
        broadcast (new MessageSent($message))->toOthers();

        // 自分のメッセージを即時表示
        $this->messages->push($message);
    }

    public function getListeners()
    {
        return [
            "echo:chat.{$this->dealing_id}, MessaageSent" => 'messageReceived'
        ];
    }

    public function messageReceived($event)
    {
        $this->messages->push(ChatMessage::find($event['messasge']['id']));
    }

    public function render()
    {
        return view('livewire.chat-room');
    }
}
