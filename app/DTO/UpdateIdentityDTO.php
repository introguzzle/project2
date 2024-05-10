<?php

namespace App\DTO;

class UpdateIdentityDTO
{
    use FromRequest;
    private ?string $currentPassword;
    private ?string $newPassword;
    private ?string $newPasswordConfirmation;

    /**
     * @param string|null $currentPassword
     * @param string|null $newPassword
     * @param string|null $newPasswordConfirmation
     */
    public function __construct(
        ?string $currentPassword,
        ?string $newPassword,
        ?string $newPasswordConfirmation
    )
    {
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
        $this->newPasswordConfirmation = $newPasswordConfirmation;
    }

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function getNewPasswordConfirmation(): ?string
    {
        return $this->newPasswordConfirmation;
    }


}
