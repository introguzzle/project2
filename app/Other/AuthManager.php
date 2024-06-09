<?php

namespace App\Other;

use App\Models\User\Identity;

class AuthManager extends \Illuminate\Auth\AuthManager
{
    public function user(): ?Identity
    {
        $t = static fn($o): ?Identity => $o;
        return $t(parent::user());
    }

    public function isAuthenticated(): bool
    {
        return $this->check();
    }

    public function isNotAuthenticated(): bool
    {
        return !$this->isAuthenticated();
    }
}
