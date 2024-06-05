<?php

namespace App\Other;

use App\Models\User\Identity;
use App\Models\User\Profile;

class Auth extends \Illuminate\Support\Facades\Auth
{
    public static function getProfile(): ?Profile
    {
        if (self::isNotAuthenticated()) {
            return null;
        }

        return self::getIdentity()?->profile;
    }

    public static function isAuthenticated(): bool
    {
        return self::check();
    }

    public static function isNotAuthenticated(): bool
    {
        return !self::check();
    }

    public static function getIdentity(): ?Identity
    {
        return (fn($o):?Identity => self::user() instanceof Identity
            ? $o : null)(self::user());
    }
}
