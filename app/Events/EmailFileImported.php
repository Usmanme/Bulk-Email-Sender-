<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailFileImported
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $fileName;
    public $file_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($fileName, $file_id)
    {
        //
        $this->fileName = $fileName;
        $this->file_id = $file_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
