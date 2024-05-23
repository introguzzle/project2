<?php

namespace App\Utils;

class BotCommand extends \Telegram\Bot\Objects\BotCommand
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

