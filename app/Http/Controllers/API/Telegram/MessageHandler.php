<?php

namespace App\Http\Controllers\API\Telegram;

use App\Http\Controllers\API\Telegram\Message\MessageDefaultHandler;
use App\Http\Controllers\API\Telegram\Message\MessageTextHandler;
use App\Http\Controllers\API\Telegram\Message\MessageTypeHandler;

use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class MessageHandler extends Handler
{
    /**
     * @param Update $update
     * @return void
     */
    public function handle(Update $update): void
    {
        $message = $update->message;

        $this->telegramService->save($message->chat);
        $this->resolveHandler($message)->handle($message);
    }

    public function resolveHandler(Message $message): MessageTypeHandler
    {
        return match (true) {
            $message->text !== null => new MessageTextHandler($this->telegramService),
            default                 => new MessageDefaultHandler($this->telegramService),
        };
    }

    public function getEntityType(): string
    {
        return 'message';
    }
}
