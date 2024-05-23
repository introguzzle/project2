<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCallbackReceivedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int|string $chatId;
    public int|string $orderId;
    public int|string $statusId;

    /**
     * @param int|string $chatId
     * @param int|string $orderId
     * @param int|string $statusId
     */
    public function __construct(
        int|string $chatId,
        int|string $orderId,
        int|string $statusId
    )
    {
        $this->chatId = $chatId;
        $this->orderId = $orderId;
        $this->statusId = $statusId;
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
