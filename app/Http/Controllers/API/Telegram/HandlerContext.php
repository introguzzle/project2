<?php

namespace App\Http\Controllers\API\Telegram;

use App\Http\Controllers\API\Telegram\TelegramHandler as Handler;

use Telegram\Bot\Objects\Update;

class HandlerContext
{
    private Handler $handler;

    public function setHandler(Handler $handler): static
    {
        $this->handler = $handler;
        return $this;
    }

    public function execute(Update $update): void
    {
        $this->handler->handle($update);
    }
}
