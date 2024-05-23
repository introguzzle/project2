<?php

namespace App\Http\Controllers\API;

use App\Events\OrderCallbackReceivedEvent;
use App\Http\Controllers\Controller;
use App\Models\TelegramClient;
use App\Services\TelegramService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

use Throwable;

class TelegramController extends Controller
{
    private TelegramService $telegramService;
    private bool $restrictOtherBots;

    /**
     * @param TelegramService $telegramService
     */
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
        $this->restrictOtherBots = (bool) env('TELEGRAM_RESTRICT_OTHER_BOTS', true);
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            $update = new Update($request->toArray());

            if ($update->message) {
                $this->save($update->message->chat);
                $this->handleMessage($update->message);
            }

            if ($update->callbackQuery) {
                $this->handleCallbackQuery($update->callbackQuery);
            }

        } catch (Throwable $throwable) {
            Log::error($throwable);
        }

        return response()->json()->setData(['status' => 'ok']);
    }

    /**
     * @throws TelegramSDKException
     */
    public function handleMessage(Message $message): void
    {
        if ($message->from->isBot && $this->restrictOtherBots) {
            return;
        }

        $response = $this->telegramService->generateResponse(
            $message->chat->id,
            $message->text,
            $message->entities?->all() ?? []
        );

        $this->telegramService->sendMessage(
            $message->chat->id,
            $response
        );
    }

    public function save(Chat $chat): ?TelegramClient
    {
        return $this->telegramService->save($chat);
    }

    public function handleCallbackQuery(
        ?CallbackQuery $callbackQuery
    ): void
    {
        list($orderId, $statusId) = explode('split', $callbackQuery->data);
        $chatId = $callbackQuery->message->chat->id;

        event(new OrderCallbackReceivedEvent(
            $chatId,
            $orderId,
            $statusId
        ));
    }
}
