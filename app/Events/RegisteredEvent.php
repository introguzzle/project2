<?php

namespace App\Events;

use App\Models\Identity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RegisteredEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Identity $identity;

    /**
     * Create a new event instance.
     */
    public function __construct(Identity $identity)
    {
        $this->identity = $identity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
