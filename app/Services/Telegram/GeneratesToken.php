<?php

namespace App\Services\Telegram;

use App\Models\Telegram\TelegramAccessToken;
use App\Models\Telegram\TelegramClient;
use App\Models\User\Profile;
use Throwable;

trait GeneratesToken
{
    public function generateToken(
        Profile $profile,
        bool    $new = false
    ): TelegramAccessToken
    {
        $telegramAccessToken = TelegramAccessToken::findByProfile($profile);

        if ($telegramAccessToken !== null) {
            if (!$new) {
                return $telegramAccessToken;
            }

            $telegramClient = TelegramClient::findByProfile(
                $telegramAccessToken->profile
            );

            $telegramClient?->setAccess(false);
            $telegramAccessToken->forceDelete();
        }

        $random = $this->random(5);

        $telegramAccessToken = new TelegramAccessToken(['token' => $random]);
        $telegramAccessToken->profile()->associate($profile);
        $telegramAccessToken->save();

        return $telegramAccessToken;
    }

    public function resetAll(): void
    {
        TelegramClient::all()->each(function (TelegramClient $telegramClient) {
            $telegramClient->setAccess(false);
        });

        TelegramAccessToken::query()->delete();
    }

    private function random(int $digits): int
    {
        $min = 10 ** ($digits - 1);
        $max = (10 ** $digits) - 1;

        try {
            return random_int($min, $max);
        } catch (Throwable) {
            return rand($min, $max);
        }
    }
}
