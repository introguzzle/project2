<?php

namespace App\DTO;

use App\Other\Requests;
use Illuminate\Http\Request;

/**
 * @mixin FromRequest
 */
readonly class LoginDTO
{
    use FromRequest;

    public ?string $login;
    public ?string $password;
    public bool $remember;

    /**
     * @param string|null $login
     * @param string|null $password
     * @param bool $remember
     */
    public function __construct(
        ?string $login,
        ?string $password,
        bool    $remember
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->remember = $remember;
    }
}
