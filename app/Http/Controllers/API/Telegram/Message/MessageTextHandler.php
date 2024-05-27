<?php

namespace App\Http\Controllers\API\Telegram\Message;

use App\Services\Telegram\TelegramService;
use Illuminate\Database\Eloquent\Collection;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\MessageEntity;

class MessageTextHandler implements MessageTypeHandler
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
        /**
         * @var Collection<MessageEntity> $entities
         */
        $entities = $message->entities;

        $response = $this->telegramService->generateResponse(
            $message->chat->id,
            $message->text,
            $entities?->all() ?? []
        );

        $this->telegramService->sendMessage(
            $message->chat->id,
            $response
        );
    }
}
