<?php

namespace App\Mail;

use App\Services\PasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public const int DEFAULT_EXPIRE_MINUTES = 10;

    private string $login;
    private string $token;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $login,
        string $token
    )
    {
        $this->login = $login;
        $this->token = $token;

        $this->to($login);
        $this->replyTo(Config::get('mail.mailers.smtp.username'));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Сброс пароля',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.reset',
            with: ['url' => $this->generateUrl()]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function generateUrl(): string
    {
        return URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(self::DEFAULT_EXPIRE_MINUTES),
            ['token' => $this->token]
        );
    }
}
