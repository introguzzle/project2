<?php

namespace App\Services\Telegram;

use Telegram\Bot\Objects\BotCommand as TelegramBotCommand;

class BotCommand extends TelegramBotCommand
{

    public function getLength(): int
    {
        return (int)$this->length;
    }

    public function getOffset(): int
    {
        return (int)$this->offset;
    }
}

