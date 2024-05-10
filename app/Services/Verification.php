<?php

namespace App\Services;

class Verification
{
    private const string HASH_FUNCTION = 'sha1';
    public static function hashEmail(string $email): string
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
