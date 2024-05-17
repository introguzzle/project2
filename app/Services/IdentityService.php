<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\PasswordResetDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UpdateIdentityDTO;
use App\Exceptions\ServiceException;
use App\Jobs\SendVerificationMailJob;
use App\Mail\PasswordResetMail;
use App\Mail\VerificationMail;
use App\Models\Identity;
use App\Models\PasswordResetToken;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Throwable;

class IdentityService
{
    public function authenticate(LoginDTO $dto): bool
    {
        $remember = $dto->getRemember() ?? false;
        $password = $dto->getPassword();

        if (($email = $dto->getEmail()) !== null) {
            return Auth::attempt(
                ['email' => $email, 'password' => $password],
                $remember
            );
        } else {
            return Auth::attempt(
                ['phone' => $dto->getPhone(), 'password' => $password],
                $remember
            );
        }
    }

    /**
     * @param RegistrationDTO $dto
     * @return void
     */
    public function register(RegistrationDTO $dto): void
    {
        DB::beginTransaction();

        try {
            $profile = $this->createProfile($dto);
            $profile->save();

            $identity = $this->createIdentity($dto);
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

        if ($identity->getEmail()) {
            $this->sendEmailVerification($identity);
        } else {
            $identity->markEmailAsVerified();
        }
    }

    /**
     * @param RegistrationDTO $dto
     * @return Profile
     */

    private function createProfile(RegistrationDTO $dto): Profile
    {
        return new Profile([
            'name' => $dto->getName()
        ]);
    }

    /**
     * @param RegistrationDTO $dto
     * @return Identity
     */

    private function createIdentity(RegistrationDTO $dto): Identity
    {
        $password = Hash::make($dto->getPassword());

        if (($email = $dto->getEmail()) !== null) {
            return new Identity(['email' => $email, 'password' => $password]);
        } else {
            return new Identity(['phone' => $dto->getPhone(), 'password' => $password]);
        }
    }

    /**
     * @param string $login
     * @return bool
     */

    public function checkLoginPresence(string $login): bool
    {
        return Identity::findByAnyCredential($login) !== null;
    }

    /**
     * @param Identity $identity
     * @return void
     */

    private function sendEmailVerification(Identity $identity): void
    {
        $verificationMail = new VerificationMail($identity);
        Queue::push(new SendVerificationMailJob($verificationMail));
    }

    /**
     * @param string $id
     * @param string $hash
     * @return bool
     */

    public function verifyEmail(string $id, string $hash): bool
    {
        $identity = Identity::find((int)$id);

        if ($identity === null) {
            return false;
        }

        $emailMatchesHash = Verification::emailMatchesHash(
            $identity->getEmailForVerification(),
            $hash
        );

        if ($emailMatchesHash) {
            $identity->markEmailAsVerified();
            return true;
        }

        return false;
    }

    public function updateIdentity(
        Identity $identity,
        UpdateIdentityDTO $dto
    ): bool
    {
        if (!Hash::check($dto->getCurrentPassword(), $identity->getAuthPassword())) {
            return false;
        }

        $identity->updatePassword($dto->getNewPassword());
        return true;
    }

    public function sendPasswordResetLink(string $email): bool
    {
        $query = Identity::query()
            ->where('email', '=', $email);

        if (!$query->exists()) {
            return false;
        }

        $token    = Str::random(100);
        $identity = $query->first();

        DB::table('password_reset_tokens')->insert([
            'identity_id' => $identity->getKey(),
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $passwordResetMail = new PasswordResetMail($email, $token);
        Mail::send($passwordResetMail);

        return true;
    }

    public function isValidToken(string $token): bool
    {
        return PasswordResetToken::query()
            ->where('token', '=', $token)
            ->where('active', '=', true)
            ->exists();
    }

    public function updatePasswordWithToken(PasswordResetDTO $dto): bool
    {
        /**
         * @var PasswordResetToken $passwordResetToken
         */
        $passwordResetToken = PasswordResetToken::query()
            ->where('token', '=', $dto->getToken())
            ->get()
            ->first();

        if ($passwordResetToken === null) {
            return false;
        }

        $identity = Identity::find($passwordResetToken->getIdentityId());

        DB::beginTransaction();

        try {
            $identity->updatePassword($dto->getPassword());
            $passwordResetToken->setExpired();
        } catch (Throwable) {
            DB::rollBack();
        }

        DB::commit();
        return true;
    }
}
