<?php

namespace App\Mail;

use App\Models\User\Identity;
use App\Services\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public const int DEFAULT_EXPIRE_MINUTES = 60;

    private Identity $identity;

    public function __construct(
        Identity $identity
    )
    {
        $this->identity = $identity;

        $this->to($identity->getEmailForVerification());
        $this->replyTo(config('mail.mailers.smtp.username'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Подтверждение почты',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verify',
            with: ['url' => $this->generateUrl()]
        );
    }

    public function attachments(): array
    {
        return [];
    }

    private function generateUrl(): string
    {
        $id = $this->identity->getKey();
        $email = $this->identity->getEmailForVerification();

        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(
                Config::get(
                    'auth.verification.expire',
                    self::DEFAULT_EXPIRE_MINUTES)
            ),

            ['id' => $id, 'hash'  => Verification::hashEmail($email)]
        );
    }
}
