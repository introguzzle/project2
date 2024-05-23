<?php

namespace App\Listeners;

use App\Events\OrderCallbackReceivedEvent;
use App\Models\Order;
use App\Models\Status;
use App\Services\OrderService;
use App\Services\TelegramService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Exceptions\TelegramSDKException;

class OrderCallbackReceivedListener implements ShouldQueue
{
    use InteractsWithQueue;
    private OrderService $orderService;
    private TelegramService $telegramService;

    /**
     * @param OrderService $orderService
     * @param TelegramService $telegramService
     */
    public function __construct(
        OrderService $orderService,
        TelegramService $telegramService
    )
    {
        $this->orderService = $orderService;
        $this->telegramService = $telegramService;
    }


    /**
     * Handle the event.
     * @throws TelegramSDKException
     */
    public function handle(OrderCallbackReceivedEvent $event): void
    {
        $orderId = (int)$event->orderId;
        $statusId = (int)$event->statusId;

        $this->orderService->setOrderStatus(
            Order::find($orderId),
            Status::find($statusId)
        );

        $this->then(
            (int) $event->chatId,
            $orderId,
            $statusId
        );
    }

    /**
     * @throws TelegramSDKException
     */
    public function then(int $chatId, int $orderId, int $statusId): void
    {
        $status = Status::find($statusId)->getName();
        $text = "Заказу $orderId был установлен статус $status";

        $this->telegramService->sendMessage(
            $chatId,
            $text
        );
    }
}
