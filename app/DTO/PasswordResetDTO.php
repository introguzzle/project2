<?php

namespace App\DTO;

class PasswordResetDTO
{
    use FromRequest;

    public readonly ?string $token;
    public readonly ?string $password;
    public readonly ?string $passwordConfirmation;

    /**
     * @param string|null $token
     * @param string|null $password
     * @param string|null $passwordConfirmation
     */
    public function __construct(
        ?string $token,
        ?string $password,
        ?string $passwordConfirmation
    )
    {
        $this->token = $token;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }


}
