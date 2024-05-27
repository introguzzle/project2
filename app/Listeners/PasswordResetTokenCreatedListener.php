<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\PasswordResetTokenCreatedEvent;
use App\Mail\PasswordResetMail;
use Illuminate\Contracts\Mail\Mailer;

class PasswordResetTokenCreatedListener extends QueueableListener
{
    private Mailer $mailer;

    /**
     * Create the event listener.
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     */
    public function handle(
        PasswordResetTokenCreatedEvent|Event $event
    ): void
    {
        $this->mailer->send(new PasswordResetMail(
            $event->passwordResetToken->identity->email,
            $event->passwordResetToken->token
        ));
    }
}
