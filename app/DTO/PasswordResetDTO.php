<?php

namespace App\DTO;

class PasswodResetDTO
{
    private ?string $token;
    private ?string $password;
    private ?string $passwordConfirmation;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): ?string
    {
        return $this->passwordConfirmation;
    }


}
