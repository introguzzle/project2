<?php

namespace App\Services;

use App\DTO\UpdateProfileDTO;
use App\Exceptions\ServiceException;
use App\Models\User\Profile;

class ProfileService
{
    public function update(
        ?Profile         $profile,
        UpdateProfileDTO $updateProfileDTO
    ): void
    {
        if ($profile === null) {
            throw new ServiceException('Profile is not present');
        }

        $profile->update([
            'address'  => $updateProfileDTO->address,
            'birthday' => $updateProfileDTO->birthday,
            'name'     => $updateProfileDTO->name
        ]);

        $profile->save();
    }
}
