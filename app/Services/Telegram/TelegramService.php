<?php

namespace App\Services\Telegram;

use App\Models\Telegram\TelegramClient;
use App\Services\Telegram\Commands\CommandStrategy;
use App\Services\Telegram\Commands\CommandStrategyResolver;
use Telegram\Bot\Api as TelegramBot;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\MessageEntity;

class TelegramService
{
    use GeneratesToken, SendsMessages, ControlsWebhook;

    protected TelegramBot $telegram;

    /**
     * @param string|null $token
     * @throws TelegramSDKException
     */
    public function __construct(?string $token = null)
    {
        $this->telegram = new TelegramBot(
            $token ?? env('TELEGRAM_BOT_TOKEN'),
            false
        );
    }

    public function save(Chat $chat): ?TelegramClient
    {
        $telegramClient = TelegramClient::findByChatId($chat->id);

        if ($telegramClient) {
            return $telegramClient;
        }

        $data = [
            'first_name' => $chat->firstName ?? null,
            'username'   => $chat->username ?? null,
            'type'       => $chat->type ?? null,
            'chat_id'    => $chat->id,
            'banned'     => false,
            'has_access' => false,
            'profile_id' => null
        ];

        return TelegramClient::create($data);
    }

    /**
     * @param string $chatId
     * @param string $text
     * @param MessageEntity[] $entities
     * @return string
     */
    public function generateResponse(
        string $chatId,
        string $text,
        array  $entities = [],
    ): string
    {
        $telegramClient = TelegramClient::findByChatId($chatId);

        foreach ($entities as $entity) {
            if ($entity->type === 'bot_command') {
                $command = new BotCommand($entity);

                return $this->handleCommand(
                    $telegramClient,
                    $chatId,
                    $command,
                    $text
                );
            }
        }

        $hasAccess = $telegramClient && $telegramClient->hasAccess;

        if ($hasAccess && ($profile = $telegramClient->profile)) {
            return "Привет, $profile->name. Нажмите на меню, чтобы увидеть доступные команды";
        }

        return 'Используйте команду /auth, чтобы пройти аутентификацию';
    }

    protected function handleCommand(
        ?TelegramClient $telegramClient,
        string          $chatId,
        BotCommand      $command,
        string          $text
    ): string
    {
        return $this
            ->resolveStrategy($command, $text)
            ->execute($telegramClient, $chatId, $text);
    }

    protected function resolveStrategy(
        BotCommand $command,
        string     $context
    ): CommandStrategy
    {
        return (new CommandStrategyResolver($command, $context))->resolve();
    }

    public function getTelegramApi(): TelegramBot
    {
        return $this->telegram;
    }
}
