<?php

namespace App\Http\Controllers\API\Telegram;

use App\Http\Controllers\Core\Controller as BaseController;
use App\Http\Requests\API\TelegramWebhookRequest;
use App\Services\Telegram\TelegramService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use JsonException;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;
use Throwable;

class Controller extends BaseController
{
    protected Dispatcher $dispatcher;
    protected HandlerContext $handlerContext;
    protected TelegramService $telegramService;

    /**
     * @param Dispatcher $dispatcher
     * @param HandlerContext $handlerContext
     * @param TelegramService $telegramService
     */
    public function __construct(
        Dispatcher $dispatcher,
        HandlerContext $handlerContext,
        TelegramService $telegramService
    )
    {
        $this->dispatcher = $dispatcher;
        $this->handlerContext = $handlerContext;
        $this->telegramService = $telegramService;
    }


    /**
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function webhook(TelegramWebhookRequest $request): Response|JsonResponse
    {
        $update = new Update($request->toArray());

        if ($update->message !== null) {
            $this->handlerContext->setHandler(
                new MessageHandler(
                    $this->telegramService
                )
            );
        }

        if ($update->callbackQuery !== null) {
            $this->handlerContext->setHandler(
                new CallbackQueryHandler(
                    $this->telegramService
                )
            );
        }

        try {
            $this->handlerContext->execute($update);
        } catch (Throwable $t) {
            Log::error($t);
            $this->fallback($update);
        }

        return $this->ok();
    }

    /**
     * @throws TelegramSDKException
     * @throws JsonException
     */
    public function fallback(Update $update): void
    {
        $message = 'Не удалось обработать сообщение';

        if ($chatId = $update->message->chat->id) {
            $this->telegramService->sendMessage($chatId, $message);
            return;
        }

        if ($chatId = $update->message->from->id) {
            $this->telegramService->sendMessage($chatId, $message);
        }
    }
}
