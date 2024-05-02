<?php

namespace App\Providers;


use App\Models\Identity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class IdentityProvider implements UserProvider
{

    public function retrieveById($identifier): ?Authenticatable
    {
        return new Identity(Identity::query()->find($identifier)->getAttributes());
    }

    public function retrieveByToken($identifier, $token): Builder|Authenticatable|null
    {
        return Identity::query()
            ->where('id', '=', $identifier)
            ->where('remember_token', '=' , $token)
            ->first();
    }

    public function updateRememberToken(Authenticatable $user, $token): void
    {
        Identity::query()
            ->where('id', '=' , $user->getAuthIdentifier())
            ->update(['remember_token' => $token]);
    }

    public function retrieveByCredentials(array $credentials): Authenticatable|null
    {
        $login = $credentials['login'] ?? null;

        return new Identity(
            Identity::query()
                ->where('login', '=', $login)
                ->first()
                ->getAttributes()
        );
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return Hash::check(
            $credentials['password'],
            $user->getAuthPassword()
        );
    }
}
