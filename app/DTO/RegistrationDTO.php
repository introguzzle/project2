<?php

namespace App\DTO;

use App\Utils\Requests;
use Illuminate\Http\Request;

class RegistrationDTO
{
    private ?string $name;
    private ?string $email;

    private ?string $phone;
    private ?string $password;
    private ?string $passwordConfirmation;

    public function __construct(
        ?string $name,
        ?string $email,
        ?string $phone,
        ?string $password,
        ?string $passwordConfirmation)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): ?string
    {
        return $this->passwordConfirmation;
    }

    public static function fromRequest(Request $request): static
    {
        return Requests::compact($request, static::class);
    }
}
