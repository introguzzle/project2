<?php

namespace App\Other;

use App\Models\User\Identity;
use App\Models\User\Profile;
use Illuminate\Support\Facades\Auth;

class Authentication extends Auth
{
    public static function profile(): ?Profile
    {
        if (static::isNotAuthenticated()) {
            return null;
        }

        return static::identity()?->profile;
    }

    public static function isAuthenticated(): bool
    {
        return static::check();
    }

    public static function isNotAuthenticated(): bool
    {
        return !static::check();
    }

    public static function identity(): ?Identity
    {
        return (static fn($static):?Identity => static::user() instanceof Identity
            ? $static : null)(static::user());
    }

    public static function isAdmin(): bool
    {
        return static::isAuthenticated()
                && static::identity()
                && static::identity()->profile->isAdmin();
    }
}
