<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels {
        SerializesModels::__serialize as protected serialize;
        SerializesModels::__unserialize as protected unserialize;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel[]|Channel
     */
    public function broadcastOn(): Channel|array
    {
        return [];
    }

    public function __serialize(): array
    {
        return $this->serialize();
    }

    public function __unserialize(array $values): void
    {
        $this->unserialize($values);
    }
}
