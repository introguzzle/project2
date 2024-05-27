<?php

namespace App\Http\Controllers\API\Auth;

class GoogleController extends SocialAuthController
{
    public function getDriverName(): string
    {
        return 'google';
    }
}
