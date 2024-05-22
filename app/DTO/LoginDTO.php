<?php

namespace App\DTO;

use App\Utils\Requests;
use Illuminate\Http\Request;

class LoginDTO
{
    use FromRequest;
    private ?string $login;
    private ?string $password;
    private bool $remember;

    /**
     * @param string|null $login
     * @param string|null $password
     * @param bool $remember
     */
    public function __construct(?string $login, ?string $password, bool $remember)
    {
        $this->login = $login;
        $this->password = $password;
        $this->remember = $remember;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function isRemember(): bool
    {
        return $this->remember;
    }
}
