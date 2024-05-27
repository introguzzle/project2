<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\BotCommand;

abstract class AbstractCommandStrategy implements CommandStrategy
{
    protected BotCommand $botCommand;

    /**
     * @param BotCommand $botCommand
     */
    public function __construct(BotCommand $botCommand)
    {
        $this->botCommand = $botCommand;
    }
}
