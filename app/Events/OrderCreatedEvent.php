<?php

namespace App\Events;

use App\Models\Order;


class OrderCreatedEvent extends Event
{
    public readonly Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
