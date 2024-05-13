<?php

namespace App\Http\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\TelegramClient;
use App\Services\TelegramService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramController extends Controller
{
    private TelegramService $telegramService;

    /**
     * @param TelegramService $telegramService
     */
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            $this->handleMessage($request->toArray());
            $this->save($request);
        } catch (Throwable $t) {
            Log::error($t);
        }

        return response()->json()->setData(['status' => 'ok']);
    }

    public function handleMessage(array $updateData): void
    {
        $messageData = $updateData['message'];

        $chatId = $messageData['chat']['id'];
        $text = $messageData['text'];

        $this->telegramService->response($chatId, $text);
    }

    public function save(Request $request): ?TelegramClient
    {
        $chatData = $request->toArray()['message']['chat'];

        return $this->telegramService->save($chatData);
    }
}
