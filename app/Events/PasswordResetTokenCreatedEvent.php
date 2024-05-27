<?php

namespace App\Events;

use App\Models\User\PasswordResetToken;

class PasswordResetTokenCreatedEvent extends Event
{
    public readonly PasswordResetToken $passwordResetToken;

    public function __construct(PasswordResetToken $passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }
}
