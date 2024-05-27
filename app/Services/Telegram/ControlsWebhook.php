<?php

namespace App\Services\Telegram;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\WebhookInfo;

trait ControlsWebhook
{
    abstract public function getTelegramApi(): Api;

    /**
     * @throws TelegramSDKException
     */
    public function setWebhook(?string $webhook = null): string
    {
        $url = $webhook ?? route('telegram.webhook');

        return $this->getTelegramApi()->setWebhook(['url' => $url]);
    }

    /**
     * @throws TelegramSDKException
     */
    public function getWebhookInfo(): WebhookInfo
    {
        return $this->getTelegramApi()->getWebhookInfo();
    }
}
