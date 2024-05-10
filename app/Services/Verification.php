<?php

namespace App\Mail;

class Verification
{
    private const string HASH_FUNCTION = 'sha1';
    public static function hashEmail(string $email)
    {
        return call_user_func(self::HASH_FUNCTION, $email);
    }

    public static function emailMatchesHash(
        string $email,
        string $hash
    ): bool
    {
        return self::hashEmail($email) === $hash;
    }
}
