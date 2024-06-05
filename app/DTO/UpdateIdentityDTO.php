<?php

namespace App\DTO;

class UpdateIdentityDTO
{
    use FromRequest;
    public readonly ?string $currentPassword;
    public readonly ?string $newPassword;
    public readonly ?string $newPasswordConfirmation;

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
}
