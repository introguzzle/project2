<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\DTO\PasswordResetDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UpdateIdentityDTO;
use App\Exceptions\ServiceException;
use App\Mail\PasswordResetMail;
use App\Mail\VerificationMail;
use App\Models\Identity;
use App\Models\PasswordResetToken;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Ui\AuthRouteMethods;
use Throwable;

class IdentityService
{
    public function authenticate(LoginDTO $dto): bool
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

    public function checkLoginPresence(string $login): bool
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
        $identity = Identity::query()->find($id);

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

    public function sendPasswordResetLink(string $login): bool
    {
        $query = Identity::query()
            ->where('login', '=', $login);

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

        Mail::send(new PasswordResetMail($login, $token));

        return true;
    }

    public function isValidToken(string $token): bool
    {
        $query  = PasswordResetToken::query()->where('token', '=', $token);
        $active = $query->get()->first()->isActive();

        return $query->exists() && $active;
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
