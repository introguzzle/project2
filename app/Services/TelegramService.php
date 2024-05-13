<?php

namespace App\Services;

use App\Models\TelegramClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Env;

class TelegramService
{
    protected const string TELEGRAM_API_URI = 'https://api.telegram.org/bot';
    protected string $token;

    private static Client $client;

    /**
     * @param string|null $token
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token ?? Env::get('TELEGRAM_BOT_TOKEN');

        if (!isset(self::$client)) {
            self::$client = new Client(['base_uri' => self::TELEGRAM_API_URI . $this->token . '/']);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function sendMessage(
        string $chatId,
        string $text
    ): string
    {
        $response = self::$client->request('POST', 'sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text
            ]
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     */
    public function setWebhook(?string $webhook = null): string
    {
        $response = self::$client->request('POST', 'setWebhook', [
            'form_params' => [
                'url' => $webhook ?? route('telegram.webhook'),
            ]
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     */
    public function getWebhookInfo(): string
    {
        $response = self::$client->request('POST', 'getWebhookInfo');

        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     */
    public function sendToAll(string $message): array
    {
        $contents = [];

        foreach (TelegramClient::all()->all() as $telegramClient) {
            $chatId = $telegramClient->getAttribute('chat_id');
            $contents[] = $this->sendMessage($chatId, $message);
        }

        return $contents;
    }

    public function save(array $chatData): ?TelegramClient
    {
        $t = fn($o): ?TelegramClient => $o;

        return $t(TelegramClient::query()->updateOrCreate(['chat_id' => $chatData['id']], [
            'chat_id' => $chatData['id'],
            'first_name' => $chatData['first_name'],
            'username' => $chatData['username'],
            'type' => $chatData['type']
        ]));
    }

    public function response(string $chatId, string $text): void
    {
        $response = match ($text) {
            default  => 'Бот умер'
        };

        $this->sendMessage($chatId, $response);
    }
}
