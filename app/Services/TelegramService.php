<?php

namespace App\Services;

use App\Models\Identity;
use App\Models\Profile;
use App\Models\TelegramAccessToken;
use App\Models\TelegramClient;
use App\Utils\BotCommand;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;

use Stringable;
use Telegram\Bot\Api as TelegramBot;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\MessageEntity;
use Telegram\Bot\Objects\WebhookInfo;
use Throwable;

class TelegramService
{
    private string $token;
    private TelegramBot $telegram;

    /**
     * @param string|null $token
     * @throws TelegramSDKException
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token ?? Env::get('TELEGRAM_BOT_TOKEN');
        $this->telegram = new TelegramBot($this->token, false);
    }

    /**
     * @throws TelegramSDKException
     */
    public function sendMessage(
        string            $chatId,
        string|Stringable $text,
        string            $parseMode = 'html',
        bool              $disableWebpagePreview = true
    ): Message
    {
        $params = [
            'chat_id'                 => $chatId,
            'text'                    => $text instanceof Stringable
                ? $text->__toString()
                : $text,

            'parse_mode'              => $parseMode,
            'disable_webpage_preview' => $disableWebpagePreview
        ];

        return $this->telegram->sendMessage($params);
    }

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(?string $webhook = null): string
    {
        $url = $webhook ?? route('telegram.webhook');

        return $this->telegram->setWebhook(['url' => $url]);
    }

    /**
     * @throws TelegramSDKException
     */
    public function getWebhookInfo(): WebhookInfo
    {
        return $this->telegram->getWebhookInfo();
    }

    /**
     * @param string|Stringable $message
     * @param string $parseMode
     * @param bool $disableWebpagePreview
     * @return bool
     */
    public function sendToAll(
        string|Stringable $message,
        string            $parseMode = 'html',
        bool              $disableWebpagePreview = true
    ): bool
    {
        foreach (TelegramClient::allWithAccess() as $telegramClient) {
            $chatId = $telegramClient->getAttribute('chat_id');
            try {
                $this->sendMessage(
                    $chatId,
                    $message,
                    $parseMode,
                    $disableWebpagePreview
                );
            } catch (TelegramSDKException $telegramSDKException) {
                Log::error($telegramSDKException);
                return false;
            }
        }

        return true;
    }

    public function save(Chat $chat): ?TelegramClient
    {
        $telegramClient = TelegramClient::findByChatId($chat->id);

        $data = [
            'first_name' => $chat->firstName ?? null,
            'username'   => $chat->username ?? null,
            'type'       => $chat->type ?? null,
        ];

        if ($telegramClient) {
            $telegramClient->update($data);

        } else {
            $telegramClient = TelegramClient::query()->create([
                'chat_id'    => $chat->id,
                'has_access' => false,
                'profile_id' => null
            ] + $data);
        }

        return $telegramClient;
    }

    public function generateToken(
        Profile $profile,
        bool    $new = false
    ): TelegramAccessToken
    {
        $telegramAccessToken = TelegramAccessToken::findByProfile($profile);

        if (!$new && $telegramAccessToken) {
            return $telegramAccessToken;
        }

        if ($new && $telegramAccessToken) {
            $telegramClient = TelegramClient::findByProfile(
                $telegramAccessToken->getRelatedProfile()
            );

            $telegramClient->restrict();
            $telegramAccessToken->forceDelete();
        }

        $random = rand(1000, 9999);

        $telegramAccessToken = new TelegramAccessToken(['token' => $random]);
        $telegramAccessToken->profile()->associate($profile);
        $telegramAccessToken->save();

        return $telegramAccessToken;
    }

    /**
     * @param string $chatId
     * @param string $text
     * @param MessageEntity[] $entities
     * @return string
     */
    public function generateResponse(
        string $chatId,
        string $text,
        array  $entities = [],
    ): string
    {
        $telegramClient = TelegramClient::findByChatId($chatId);

        foreach ($entities as $entity) {
            if ($entity->type === 'bot_command') {
                $command = new BotCommand($entity);

                return $this->handleCommand(
                    $telegramClient,
                    $chatId,
                    $command,
                    $text
                );
            }
        }

        if ($telegramClient?->hasAccess()) {
            return "Привет, {$telegramClient->getRelatedProfile()->getName()}";
        } else {
            return 'Используйте команду /auth, чтобы пройти аутентификацию.';
        }
    }

    private function handleCommand(
        ?TelegramClient $telegramClient,
        string          $chatId,
        BotCommand      $command,
        string          $text
    ): string
    {
        $commandName = substr(
            $text,
            $command->getOffset(),
            $command->getLength()
        );

        if ($commandName === '/auth') {
            if ($telegramClient?->hasAccess()) {
                return "Вы уже прошли аутентификацию";
            }

            try {
                $credentials = substr(
                    $text,
                    $command->getLength() + 1
                );

                list($login, $token) = explode(' ', $credentials);

                if ($name = $this->bindClient($chatId, $login, $token)) {
                    return "Привет, $name";
                }
            } catch (Throwable $t) {
                Log::error($t);
                return 'Не удалось проверить данные';
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
        $profile = Identity::findProfile($login);
        $telegramAccessToken = TelegramAccessToken::findToken($token);

        if (!$profile || !$telegramAccessToken) {
            return null;
        }

        $relatedProfile = $telegramAccessToken->getRelatedProfile();

        $id    = (int) $profile->getId();
        $other = (int) $relatedProfile->getId();

        if ($id === $other) {
            $telegramClient = TelegramClient::findByChatId($chatId);
            $telegramClient->grantAccess();
            $telegramClient->profile()->associate($relatedProfile);
            $telegramClient->save();

            return $profile->getName();
        }

        return null;
    }
}
