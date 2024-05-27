<?php

namespace App\Services\Telegram;

use App\Mail\Telegram\Notification;
use App\Models\Telegram\TelegramClient;
use JsonException;
use Stringable;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;

trait SendsMessages
{
    abstract public function getTelegramApi(): Api;

    /**
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function sendMessage(
        string            $chatId,
        string|Stringable $text,
        string            $parseMode = 'html',
        array             $replyMarkup = []
    ): Message
    {
        $params = [
            'chat_id'                 => $chatId,
            'text'                    => toString($text),
            'parse_mode'              => $parseMode,
            'disable_webpage_preview' => true,
        ];

        if (!empty($replyMarkup)) {
            $params['reply_markup'] = json_encode($replyMarkup, JSON_THROW_ON_ERROR);
        }

        return $this->getTelegramApi()->sendMessage($params);
    }

    /**
     * @throws TelegramSDKException|JsonException
     */
    public function sendNotification(
        string       $chatId,
        Notification $notification,
        string       $parseMode = 'html'
    ): Message
    {
        return $this->sendMessage(
            $chatId,
            $notification->getContent(),
            $parseMode,
            $notification->getButtons(),
        );
    }

    /**
     * @param string|Stringable|Notification $message
     * @return bool
     * @throws JsonException
     */
    public function sendToAll(
        string|Stringable|Notification $message
    ): bool
    {
        $strategy = $message instanceof Notification
            ? 'sendNotification'
            : 'sendMessage';

        $allWithAccess = TelegramClient::allWithAccess();

        foreach ($allWithAccess as $telegramClient) {
            try {
                $this->{$strategy}($telegramClient->chatId, $message);

            } catch (TelegramSDKException) {
                return false;
            }
        }

        return true;
    }
}
