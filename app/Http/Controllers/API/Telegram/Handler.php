<?php

namespace App\Http\Controllers\API\Telegram;

use App\Services\Telegram\TelegramService;

use Illuminate\Contracts\Events\Dispatcher;

abstract class Handler implements TelegramHandler
{
    protected Dispatcher $dispatcher;
    protected TelegramService $telegramService;

    /**
     * @param Dispatcher $dispatcher
     * @param TelegramService $telegramService
     */
    public function __construct(
        Dispatcher $dispatcher,
        TelegramService $telegramService
    )
    {
        $this->dispatcher = $dispatcher;
        $this->telegramService = $telegramService;
    }
}
