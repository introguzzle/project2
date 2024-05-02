<?php

namespace App\Services;

use App\DTO\RegistrationDTO;
use App\Exceptions\ServiceException;
use App\Models\Identity;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegistrationService
{
    public function register(RegistrationDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $profile = $this->newProfile($dto);
            $profile->save();

            $identity = $this->newIdentity($dto);
            $identity->profile()->associate($profile);
            $identity->save();
        } catch (Throwable $t) {
            DB::rollBack();

            throw new ServiceException(
                "Failed to save profile and then identity",
                0,
                $t
            );
        }

        DB::commit();
    }

    private function newProfile(RegistrationDTO $dto): Profile
    {
        return new Profile([
            'name' => $dto->getName()
        ]);
    }

    private function newIdentity(RegistrationDTO $dto): Identity
    {
        $login = $dto->getEmail() ?: $dto->getPhone();

        return new Identity([
                'login'    => $login,
                'password' => Hash::make($dto->getPassword())
        ]);
    }

    public function isLoginPresent(string $login): bool
    {
        return Identity::query()
            ->where('login', '=', $login)
            ->exists();
    }
}
