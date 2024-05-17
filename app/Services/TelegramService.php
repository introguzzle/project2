<?php

namespace App\Services;

use App\Models\Identity;
use App\Models\Profile;
use App\Models\TelegramAccessToken;
use App\Models\TelegramClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;
use Stringable;

class TelegramService
{
    protected const string ENDPOINT = 'https://api.telegram.org/bot';
    protected string $token;

    private static Client $client;

    /**
     * @param string|null $token
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token ?? Env::get('TELEGRAM_BOT_TOKEN');

        if (!isset(self::$client)) {
            self::$client = new Client(['base_uri' => self::ENDPOINT . $this->token . '/']);
        }
    }

    /**
     * @throws GuzzleException
     */
    public function sendMessage(
        string            $chatId,
        string|Stringable $text,
        string            $parseMode = 'html',
        bool              $disableWebpagePreview = true
    ): string
    {
        $response = self::$client->request('POST', 'sendMessage', [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $text instanceof Stringable ? $text->__toString() : $text,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disableWebpagePreview
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
     * @return string[]
     */
    public function sendToAll(
        string|Stringable $message,
        string            $parseMode = 'html',
        bool              $disableWebpagePreview = true
    ): array
    {
        $contents = [];

        foreach (TelegramClient::all()->all() as $telegramClient) {
            if ($telegramClient->hasAccess()) {
                $chatId = $telegramClient->getAttribute('chat_id');
                try {
                    $contents[] = $this->sendMessage(
                        $chatId,
                        $message,
                        $parseMode,
                        $disableWebpagePreview
                    );
                } catch (GuzzleException $guzzleException) {
                    Log::error($guzzleException);
                }
            }
        }

        return $contents;
    }

    public function save(array $chatData): ?TelegramClient
    {
        $telegramClient = TelegramClient::query()
            ->where('chat_id', '=', $chatData['id'])
            ->first();

        if ($telegramClient) {
            $telegramClient->update([
                'first_name' => $chatData['first_name'],
                'username'   => $chatData['username'],
                'type'       => $chatData['type'],
            ]);
        } else {
            $telegramClient = TelegramClient::query()->create([
                'chat_id'    => $chatData['id'],
                'first_name' => $chatData['first_name'],
                'username'   => $chatData['username'],
                'type'       => $chatData['type'],
                'has_access' => false,
                'profile_id' => null
            ]);
        }

        return TelegramClient::hint()($telegramClient);
    }

    public function generateToken(
        Profile $profile,
    ): TelegramAccessToken
    {
        $token = TelegramAccessToken::query()
            ->where('profile_id', '=', $profile->getId())
            ->first();

        if ($token) {
            return (fn($t): TelegramAccessToken => $t)($token);
        }

        $random = rand(1000, 9999);

        $token = new TelegramAccessToken(['token' => $random]);
        $token->profile()->associate($profile);
        $token->save();

        return $token;
    }

    /**
     * @param string $chatId
     * @param string $text
     * @param array|null $entities
     * @return string
     */
    public function generateResponse(
        string $chatId,
        string $text,
        ?array $entities = null,
    ): string
    {
        $telegramClient = TelegramClient::findByChatId($chatId);

        if ($entities) {
            $commandEntity = null;

            foreach ($entities as $entity) {
                if ($entity['type'] === 'bot_command') {
                    $commandEntity = $entity;
                }
            }

            if ($commandEntity === null) {
                return 'Неизвестная команда';
            }

            return $this->handleCommand(
                $telegramClient,
                $chatId,
                $commandEntity,
                $text
            );
        }

        if ($telegramClient?->hasAccess()) {
            return "Привет, {$telegramClient->getRelatedProfile()->getName()}";
        } else {
            return 'Используйте команду /auth, чтобы пройти аутентификацию.';
        }
    }

    /**
     * @param TelegramClient|null $telegramClient
     * @param string $chatId
     * @param array $commandEntity
     * @param string $text
     * @return string
     */

    private function handleCommand(
        ?TelegramClient $telegramClient,
        string $chatId,
        array $commandEntity,
        string $text
    ): string
    {
        $command = substr($text, $commandEntity['offset'], $commandEntity['length']);

        if ($command === '/auth') {
            if ($telegramClient?->hasAccess()) {
                return "Вы уже прошли аутентификацию";
            }

            $credentials = substr($text, $commandEntity['length'] + 1);
            list($login, $token) = explode(' ', $credentials);

            if ($name = $this->bindClient($chatId, $login, $token)) {
                return "Привет, $name";
            }

            return 'Не удалось проверить данные';

        } else {
            return 'Неизвестная команда';
        }
    }

    /**
     * @param string $chatId
     * @param string $login
     * @param string $token
     * @return string|null
     */

    private function bindClient(
        string $chatId,
        string $login,
        string $token
    ): ?string
    {
        $byLogin = Identity::findByAnyCredential($login);
        $byToken = TelegramAccessToken::findToken($token);

        if ($byLogin && $byToken && $byLogin->getId() === $byToken->getId()) {
            $telegramClient = TelegramClient::findByChatId($chatId);
            $telegramClient->grantAccess();
            $telegramClient->profile()->associate($byToken);
            $telegramClient->save();

            return $byLogin->getName();
        }

        return null;
    }
}
