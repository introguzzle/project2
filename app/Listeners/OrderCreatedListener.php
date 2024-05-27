<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\OrderCreatedEvent;
use App\Mail\Telegram\OrderNotification;
use App\Services\Telegram\TelegramService;
use JsonException;

class OrderCreatedListener extends QueueableListener
{
    private TelegramService $telegramService;

    /**
     * Create the event listener.
     */
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Handle the event.
     * @throws JsonException
     */
    public function handle(OrderCreatedEvent|Event $event): void
    {
        $this->telegramService->sendToAll(new OrderNotification(
            $event->order
        ));
    }
}
