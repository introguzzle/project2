<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Mail\TelegramOrderNotification;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedListener implements ShouldQueue
{
    use InteractsWithQueue;

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
     */
    public function handle(OrderCreatedEvent $event): void
    {
        $this->telegramService->sendToAll(new TelegramOrderNotification(
            $event->order
        ));
    }
}
