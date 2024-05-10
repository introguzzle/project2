<?php

namespace App\Services;

use App\DTO\UpdateProfileDTO;
use App\Exceptions\ServiceException;
use App\Models\Identity;
use App\Models\Profile;
use App\ModelView\ProfileView;

class ProfileService
{
    /**
     * @param Identity $identity
     * @return Profile|null
     */

    public function acquireProfileByIdentity(Identity $identity): ?Profile
    {
        return $this->acquireByLogin($identity->getAuthIdentifier());
    }

    /**
     * @param string $login
     * @return Profile|null
     */

    public function acquireByLogin(string $login): ?Profile
    {
        return (fn($object): ?Profile => $object)(Profile::query()->find(
            Identity::query()
                ->where('login', '=', $login)
                ->first()
                ->getAttribute('profile_id')
        ));
    }

    /**
     * @param Identity $identity
     * @return ProfileView|null
     */

    public function createProfileViewByIdentity(Identity $identity): ?ProfileView
    {
        return new ProfileView(
            $this->acquireProfileByIdentity($identity),
            $identity
        );
    }

    public function createProfileViewByProfile(Profile $profile): ?ProfileView
    {
        return new ProfileView(
            $profile,
            (fn($o): ?Identity => $o)(Identity::query()
                ->where('profile_id', '=', $profile->getId())
                ->first())
        );
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
            'birthday' => $updateProfileDTO->getBirthDay(),
            'name' => $updateProfileDTO->getName()
        ]);

        $profile->save();
    }

    public function createProfileViewById(
        int $profileId
    ): ?ProfileView
    {
        return new ProfileView(
            Profile::find($profileId),
            Identity::query()->where('profile_id', '=', $profileId)->first()
        );
    }
}
