<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\RegistrationDTO;
use App\Exceptions\ServiceException;
use App\Mail\Verification;
use App\Mail\VerificationMail;
use App\Models\Identity;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

class RegistrationService
{
    public function login(LoginDTO $dto): bool
    {
        $login = $dto->getEmail() ?: $dto->getPhone();

        return Auth::attempt(
            ['login' => $login, 'password' => $dto->getPassword()],
            $dto->getRemember() ?? false
        );
    }

    /**
     * @param RegistrationDTO $dto
     * @return void
     */
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

        $this->sendEmailVerification($identity);
    }

    /**
     * @param RegistrationDTO $dto
     * @return Profile
     */

    private function newProfile(RegistrationDTO $dto): Profile
    {
        return new Profile([
            'name' => $dto->getName()
        ]);
    }

    /**
     * @param RegistrationDTO $dto
     * @return Identity
     */

    private function newIdentity(RegistrationDTO $dto): Identity
    {
        $login = $dto->getEmail() ?: $dto->getPhone();

        return new Identity([
                'login'    => $login,
                'password' => Hash::make($dto->getPassword())
        ]);
    }

    /**
     * @param string $login
     * @return bool
     */

    public function isLoginPresent(string $login): bool
    {
        return Identity::query()
            ->where('login', '=', $login)
            ->exists();
    }

    /**
     * @param Identity $identity
     * @return void
     */

    private function sendEmailVerification(Identity $identity): void
    {
        Mail::send(new VerificationMail($identity));
    }

    /**
     * @param string $id
     * @param string $hash
     * @return bool
     */

    public function verifyEmail(string $id, string $hash): bool
    {
        /**
         * @var Identity $identity
         */
        $identity = (fn($object): ?Identity => $object)(Identity::query()->find($id));

        if (Verification::emailMatchesHash($identity->getEmailForVerification(), $hash)) {
            $identity->markEmailAsVerified();
            return true;
        }

        return false;
    }
}