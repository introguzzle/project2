<?php

namespace App\Http\Controllers\API\Telegram\Message;

use App\Services\Telegram\TelegramService;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;

class MessageDefaultHandler implements MessageTypeHandler
{
    private TelegramService $telegramService;

    /**
     * @param TelegramService $telegramService
     */
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }


    /**
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function handle(Message $message): void
    {
        $this->telegramService->sendMessage(
            $message->chat->id,
            'Неизвестный тип сообщения'
        );
    }
}
