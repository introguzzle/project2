<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\RegisteredEvent;
use App\Mail\VerificationMail;
use Illuminate\Contracts\Mail\Mailer;

class RegisteredListener extends QueueableListener
{
    private Mailer $mailer;

    /**
     * @param Mailer $mailer
     */
    public function __construct(
        Mailer $mailer
    )
    {
        $this->mailer = $mailer;
    }


    public function handle(RegisteredEvent|Event $event): void
    {
        $this->mailer->send(new VerificationMail($event->identity));
    }
}
