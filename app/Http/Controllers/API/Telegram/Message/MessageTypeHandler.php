<?php

namespace App\Http\Controllers\API\Telegram\Message;

use Telegram\Bot\Objects\Message;

interface MessageTypeHandler
{
    public function handle(Message $message): void;
}
