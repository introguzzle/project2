<?php

namespace App\Jobs;

use App\Mail\VerificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendVerificationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private VerificationMail $verificationMail;

    /**
     * Create a new job instance.
     */
    public function __construct(VerificationMail $verificationMail)
    {
        $this->verificationMail = $verificationMail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::send($this->verificationMail);
    }
}
