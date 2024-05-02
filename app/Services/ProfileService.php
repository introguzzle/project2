<?php

namespace App\Services;

use App\Models\Identity;
use App\Models\Profile;
use App\ModelView\ProfileView;
use Illuminate\Contracts\Auth\Authenticatable;

class ProfileService
{
    public function acquireProfileByIdentity(Authenticatable $authenticatable): ?Profile
    {
        return $this->acquireProfileByLogin($authenticatable->getAuthIdentifier());
    }

    public function acquireProfileByLogin(string $login): ?Profile
    {
        return Profile::query()->find(
            Identity::query()
                ->where('login', '=', $login)
                ->first()
                ->getAttribute('profile_id')
        )->first();
    }

    public function acquireProfileView(Authenticatable $authenticatable): ?ProfileView
    {
        return new ProfileView(
            $this->acquireProfileByIdentity($authenticatable),
            $authenticatable
        );
    }
}
