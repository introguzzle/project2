<?php

namespace App\Utils;

use App\Models\Profile;

class Auth extends \Illuminate\Support\Facades\Auth
{
    public static function getProfile(): ?Profile
    {
        if (self::user() === null) {
            return null;
        }

        return Profile::query()
            ->find(self::user()->getAttribute('profile_id'))
            ->first();
    }
}
