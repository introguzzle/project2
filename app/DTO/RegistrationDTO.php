<?php

namespace App\DTO;

use App\Other\Requests;
use Illuminate\Http\Request;

class RegistrationDTO
{
    use FromRequest;
    public readonly ?string $name;
    public readonly ?string $email;

    public readonly ?string $phone;
    public readonly ?string $password;
    public readonly ?string $passwordConfirmation;

    public function __construct(
        ?string $name,
        ?string $email,
        ?string $phone,
        ?string $password,
        ?string $passwordConfirmation = null
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }
}
