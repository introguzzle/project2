<?php

namespace App\Services;

use App\DTO\LoginDTO;
use App\Models\Identity;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginService
{

    public function login(LoginDTO $dto): bool
    {
        $login = $dto->getEmail() ?: $dto->getPhone();

        return Auth::attempt(
            ['login' => $login, 'password' => $dto->getPassword()],
            $dto->getRemember()
        );
    }
}
