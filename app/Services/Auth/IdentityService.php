<?php

namespace App\Services\Auth;

use App\DTO\LoginDTO;
use App\DTO\PasswordResetDTO;
use App\DTO\RegistrationDTO;
use App\DTO\UpdateIdentityDTO;
use App\Models\Role;
use App\Models\User\Identity;
use App\Models\User\PasswordResetToken;
use App\Models\User\Profile;
use App\Services\Verification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class IdentityService
{
    /**
     * @throws InvalidCredentialsException
     * @throws EmailNotVerifiedException
     */
    public function authenticate(LoginDTO $dto): void
    {
        $login = $dto->getLogin();
        $password = $dto->getPassword();
        $remember = $dto->isRemember() ?? false;

        $identity = Identity::findByCredential($login);

        if (($identity !== null) && $identity->email && !$identity->hasVerifiedEmail()) {
            throw new EmailNotVerifiedException();
        }

        $attemptByEmail = Auth::attempt([
            'email' => $login,
                'password' => $password],
                $remember
        );

        $attemptByPhone = Auth::attempt([
            'phone' => $login,
            'password' => $password],
            $remember
        );

        $success = $attemptByPhone || $attemptByEmail;

        if (!$success) {
            throw new InvalidCredentialsException();
        }
    }

    /**
     * Регистрация нового пользователя.
     *
     * @param RegistrationDTO $dto Объект DTO с данными регистрации.
     * @param Role|string|int $role Идентификатор роли, имя роли или объект роли.
     * @param string|null $service
     * @param int|string|null $serviceValue
     *
     * @return Identity|false Возвращает true, если регистрация успешна.
     */
    public function register(
        RegistrationDTO $dto,
        Role|string|int $role,
        ?string         $service = null,
        int|string|null $serviceValue = null
    ): Identity|false
    {
        $resolvedRole = $this->resolveRole($role);

        DB::beginTransaction();

        try {
            $profile = $this->createProfile(
                $dto,
                $resolvedRole,
                $service,
                $serviceValue
            );

            $profile->save();

            $identity = $this->createIdentity($dto);
            $identity->profile()->associate($profile);
            $identity->save();
        } catch (Throwable $t) {
            Log::error($t);
            DB::rollBack();
            return false;
        }

        DB::commit();

        return $identity;
    }

    public function registerGuest(
        string $name
    ): Identity|false
    {
        $name = 'guest_' . $name;

        return $this->register(
            new RegistrationDTO(
                $name,
                null,
                null,
                Str::random(),
            ),
            Role::GUEST,
            false
        );
    }

    /**
     * @param RegistrationDTO $dto
     * @param Role $role
     * @param string|null $service
     * @param int|string|null $serviceValue
     * @return Profile
     */

    private function createProfile(
        RegistrationDTO $dto,
        Role            $role,
        ?string         $service,
        int|string|null $serviceValue
    ): Profile
    {
        $attributes = ['name' => $dto->getName()];

        if ($service && $serviceValue) {
            $serviceAttribute = $service . '_id';
            $attributes[$serviceAttribute] = $serviceValue;
        }

        $profile = new Profile($attributes);
        $profile->role()->associate($role);

        return $profile;
    }

    public function registerViaService(
        RegistrationDTO $dto,
        string          $service,
        int|string      $serviceValue
    ): Identity|false
    {
        return $this->register(
            $dto,
            Role::USER,
            $service,
            $serviceValue
        );
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
        }

        return new Identity(['phone' => $dto->getPhone(), 'password' => $password]);
    }

    /**
     * @param string $login
     * @return bool
     */

    public function checkLoginPresence(
        string $login
    ): bool
    {
        return Identity::findProfile($login) !== null;
    }

    /**
     * @param string $id
     * @param string $hash
     * @return bool
     */

    public function verifyEmail(
        string $id,
        string $hash
    ): bool
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

    /**
     * @param Identity $identity
     * @param UpdateIdentityDTO $dto
     * @return bool
     */

    public function updateIdentity(
        Identity          $identity,
        UpdateIdentityDTO $dto
    ): bool
    {
        if (!Hash::check($dto->getCurrentPassword(), $identity->getAuthPassword())) {
            return false;
        }

        $identity->updatePassword($dto->getNewPassword());
        return true;
    }

    /**
     * @param string $email
     * @return bool
     */

    public function requestPasswordReset(
        string $email
    ): bool
    {
        $identity = Identity::findUnique('email', $email);
        if ($identity === null) {
            return false;
        }

        PasswordResetToken::query()->create([
            'identity_id' => $identity->getAttribute('id'),
            'token'       => Str::random(100)
        ]);

        return true;
    }

    /**
     * @param PasswordResetDTO $dto
     * @return bool
     */

    public function updatePassword(
        PasswordResetDTO $dto
    ): bool
    {
        $passwordResetToken = PasswordResetToken::findUnique('token', $dto->getToken());

        if ($passwordResetToken === null) {
            return false;
        }

        $identity = Identity::find($passwordResetToken->identity->id);

        DB::beginTransaction();

        try {
            $identity->updatePassword($dto->getPassword());
            $passwordResetToken->setExpired();
        } catch (Throwable $t) {
            Log::error($t);
            DB::rollBack();
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @param int|string|Role $role
     * @return Role
     */
    public function resolveRole(int|string|Role $role): Role
    {
        return match (true) {
            is_int($role)    => Role::find($role),
            is_string($role) => Role::findByName($role),
            $role instanceof Role  => $role,

            default => throw new InvalidArgumentException('Invalid role type provided.')
        };
    }
}
