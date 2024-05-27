<?php

namespace App\Services\Telegram\Commands;

use App\Models\Telegram\TelegramClient;

interface CommandStrategy
{
    public function execute(
        ?TelegramClient $telegramClient,
        string          $chatId,
        string          $text
    ): string;
}
