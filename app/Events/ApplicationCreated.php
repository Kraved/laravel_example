<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $applicationId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $applicationId)
    {
        $this->applicationId = $applicationId;
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

    public function getApplicationId()
    {
        return $this->applicationId;
    }
}
