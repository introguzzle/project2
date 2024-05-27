<?php

namespace App\Services\Telegram\Commands;

use App\Exceptions\ServiceException;
use App\Models\Telegram\TelegramAccessToken;
use App\Models\Telegram\TelegramClient;
use App\Models\User\Identity;
use App\Models\User\Profile;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthCommandStrategy extends AbstractCommandStrategy
{
    public function execute(
        ?TelegramClient $telegramClient,
        string          $chatId,
        string          $text
    ): string
    {
        if ($telegramClient && $telegramClient->hasAccess()) {
            return "Вы уже прошли аутентификацию";
        }

        try {
            $credentials = substr(
                $text,
                $this->botCommand->getLength() + 1
            );

            [$login, $token] = explode(' ', $credentials);

            if ($profile = $this->bindClient($chatId, $login, $token)) {
                return "Привет, {$profile->getName()}";
            }
        } catch (Throwable $t) {
            if (!isset($login, $token)) {
                return 'Некорректный формат ввода';
            }

            Log::error('Не получилось обработать данные. Вина во входных данных?');
            Log::error($t);
            return 'Внутренняя ошибка сервера';
        }

        return 'Не удалось проверить данные';
    }

    private function bindClient(
        string $chatId,
        string $login,
        string $token
    ): ?Profile
    {
        $profile = Identity::findProfile($login);
        $telegramAccessToken = TelegramAccessToken::findToken($token);

        if ($profile === null || $telegramAccessToken === null) {
            return null;
        }

        $id = $profile->id;
        $other = $telegramAccessToken->profile->id;

        if ($id === $other) {
            $telegramClient = TelegramClient::findByChatId($chatId);

            if ($telegramClient === null) {
                throw new ServiceException(
                    'Такого телеграм-клиента не удалось найти'
                );
            }

            $telegramClient->setAccess(true);
            $telegramClient->profile()->associate($telegramAccessToken->profile);
            $telegramClient->save();

            return $profile;
        }

        return null;
    }
}
