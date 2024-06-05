<?php

namespace App\Http\Controllers\API\Telegram;

use App\Services\Telegram\TelegramService;


abstract class Handler implements TelegramHandler
{
    protected TelegramService $telegramService;

    /**
     * @param TelegramService $telegramService
     */
    public function __construct(
        TelegramService $telegramService
    )
    {
        $this->telegramService = $telegramService;
    }
}
