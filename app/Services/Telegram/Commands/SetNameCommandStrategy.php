<?php

namespace App\Services\Telegram\Commands;

use App\Models\Telegram\TelegramClient;

class SetNameCommandStrategy extends AbstractCommandStrategy
{

    public function execute(
        ?TelegramClient $telegramClient,
        string $chatId,
        string $text
    ): string
    {
        return 'set';
    }
}
