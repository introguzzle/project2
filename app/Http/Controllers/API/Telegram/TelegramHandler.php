<?php

namespace App\Http\Controllers\API\Telegram;

use Telegram\Bot\Objects\Update;

interface TelegramHandler
{

    public function handle(Update $update): void;
    public function getEntityType(): string;
}
