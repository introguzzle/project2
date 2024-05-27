<?php

namespace App\Providers;


use App\Models\User\Identity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class IdentityProvider implements UserProvider
{

    public function retrieveById($identifier): ?Authenticatable
    {
        return Identity::query()
            ->where('login', '=', $identifier)
            ->first();
    }

    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        return Identity::query()
            ->where('login', '=', $identifier)
            ->where('remember_token', '=', $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        Identity::query()
            ->where('login', '=' , $user->getAuthIdentifier())
            ->update(['remember_token' => $token]);
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        return $this->retrieveById($credentials['login']);
    }

    public function validateCredentials(
        Authenticatable $user,
        array $credentials
    ): bool
    {
        return Hash::check(
            $credentials['password'],
            $user->getAuthPassword()
        );
    }
}
