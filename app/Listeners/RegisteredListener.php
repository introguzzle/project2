<?php

namespace App\Listeners;

use App\Events\RegisteredEvent;
use App\Services\IdentityService;

class RegisteredListener
{
    private IdentityService $identityService;

    public function __construct(IdentityService $identityService)
    {
        $this->identityService = $identityService;
    }

    public function handle(RegisteredEvent $event): void
    {
        $this->identityService->sendEmailVerification($event->identity);
    }
}
