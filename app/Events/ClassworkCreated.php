<?php

namespace App\Events;

use App\Models\Classwork;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// don't use to broadcast chanel in event and notification
class ClassworkCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Classwork $classwork)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            //give me detail about user used for chat
//            new PresenceChannel('channel'),
            new PrivateChannel('classroom.'.$this->classwork->classroom_id),
        ];
    }

    public function broadcastAs()
    {
        //name of event
        return 'classwork-created';
    }

    public function broadcastWith():array
    {
        return [
          'id' => $this->classwork->id,
          'title' => $this->classwork->title,
            'user' => $this->classwork->user,
            'classroom' => $this->classwork->classroom,
        ];
    }
}
