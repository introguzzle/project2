<?php

namespace App\Services;

use App\DTO\UpdateProfileDTO;
use App\Exceptions\ServiceException;
use App\Models\Identity;
use App\Models\Profile;

class ProfileService
{
    /**
     * @param string $login
     * @return Profile|null
     */

    public function acquireByLogin(string $login): ?Profile
    {
        return (fn($object): ?Profile => $object)(Profile::query()->find(
            Identity::query()
                ->where('phone', '=', $login)
                ->orWhere('email', '=', $login)
                ->first()
                ->getAttribute('profile_id')
        ));
    }


    public function update(
        ?Profile $profile,
        UpdateProfileDTO $updateProfileDTO
    ): void
    {
        if ($profile === null) {
            throw new ServiceException('Profile is not present');
        }

        $profile->update([
            'address' => $updateProfileDTO->getAddress(),
            'birthday' => $updateProfileDTO->getBirthday(),
            'name' => $updateProfileDTO->getName()
        ]);

        $profile->save();
    }
}
