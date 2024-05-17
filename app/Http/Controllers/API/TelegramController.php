<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TelegramClient;
use App\Services\TelegramService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;
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
        $this->restrictOtherBots = (bool)Env::get('TELEGRAM_RESTRICT_OTHER_BOTS', true);
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            $this->save($request);
            $this->handleWebhook($request);
        } catch (Throwable $t) {
            Log::error($t);
        }

        return response()->json()->setData(['status' => 'ok']);
    }

    /**
     * @throws GuzzleException
     */
    public function handleWebhook(Request $request): void
    {
        $updateData = $request->toArray();
        $messageData = $updateData['message'];

        if ($messageData['from']['is_bot'] && $this->restrictOtherBots) {
            return;
        }

        $chatId = $messageData['chat']['id'];

        $text = $messageData['text'];
        $entities = $messageData['entities'] ?? null;

        $response = $this->telegramService->generateResponse($chatId, $text, $entities);
        $this->telegramService->sendMessage($chatId, $response);
    }

    public function save(Request $request): ?TelegramClient
    {
        $chatData = $request->toArray()['message']['chat'];
        return $this->telegramService->save($chatData);
    }
}
