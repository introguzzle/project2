<?php

namespace App\Utils;

use App\Models\Identity;
use App\Models\Profile;

class Auth extends \Illuminate\Support\Facades\Auth
{
    public static function getProfile(): ?Profile
    {
        if (self::user() === null && self::getUser() === null) {
            return null;
        }

        return (fn($o): ?Profile => $o)(Profile::query()
            ->find(self::getIdentity()->getAttribute('profile_id')));
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
