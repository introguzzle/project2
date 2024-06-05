<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;


class OrderCreatedEvent extends BroadcastEvent
{
    public readonly Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function broadcastOn(): Channel|array
    {
        return ['orders'];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
