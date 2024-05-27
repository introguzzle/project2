<?php

namespace App\Listeners;

use App\Events\Event;
use Throwable;

interface Listener
{
    /**
     * @throws Throwable
     * @param Event $event
     * @return void
     */
    public function handle(Event $event): void;
}
