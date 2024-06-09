<?php

namespace App\Http\Controllers\API\Telegram;

use App\Models\Order;
use App\Models\Status;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

class CallbackQueryHandler extends Handler
{
    /**
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function handle(Update $update): void
    {
        $callbackQuery = $update->callbackQuery;

        [$orderId, $statusId] = explode('split', $callbackQuery->data);
        $chatId = $callbackQuery->message->chat->id;

        Order::find((int) $orderId)?->updateStatus((int) $statusId);

        $status = Status::find((int) $statusId)->name;
        $text = "Заказу $orderId был установлен статус $status";

        $this->telegramService->sendMessage(
            $chatId,
            $text
        );
    }

    public function getEntityType(): string
    {
        return 'callbackQuery';
    }
}
