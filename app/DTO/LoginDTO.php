<?php

namespace App\DTO;

use App\Utils\Requests;
use Illuminate\Http\Request;

class LoginDTO
{
    private ?string $phone;
    private ?string $email;
    private ?string $password;
    private bool $remember;

    /**
     * @param string|null $phone
     * @param string|null $email
     * @param string|null $password
     * @param bool $remember
     */
    public function __construct(
        ?string $phone,
        ?string $email,
        ?string $password,
        bool $remember
    )
    {
        $this->phone = $phone;
        $this->email = $email;
        $this->password = $password;
        $this->remember = $remember;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRemember(): bool
    {
        return $this->remember;
    }

    public static function fromRequest(Request $request): static
    {
        return Requests::compact($request, static::class);
    }
}
