<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Notification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $message;
    public $title;
    public $salon;
    public $artist;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $title, $salon, $artist, $type)
    {
        $this->message = $message;
        $this->title = $title;
        $this->salon = $salon;
        $this->artist = $artist;
        $this->type = $type;
    }

    /**
     * @return string[]
     */
    public function broadcastOn()
    {
        return ['admin-channel', 'salon-channel', 'artist-channel'];
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'event';
    }
}
