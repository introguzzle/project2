<?php

namespace App\Jobs;

use App\Mail\PasswordResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PasswordResetMail $passwordResetMail;

    /**
     * @param PasswordResetMail $passwordResetMail
     */
    public function __construct(PasswordResetMail $passwordResetMail)
    {
        $this->passwordResetMail = $passwordResetMail;
    }

    public function handle(): void
    {
        Mail::send($this->passwordResetMail);
    }
}
