<?php

namespace App\Services\Telegram\Commands;

use App\Models\Telegram\TelegramClient;

class DefaultCommandStrategy implements CommandStrategy
{

    public function execute(
        ?TelegramClient $telegramClient,
        string          $chatId,
        string          $text
    ): string
    {
        return 'Неизвестная команда';
    }
}
