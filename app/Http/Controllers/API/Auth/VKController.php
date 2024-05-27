<?php

namespace App\Http\Controllers\API\Auth;

class VKController extends SocialAuthController
{
    public function getDriverName(): string
    {
        return 'vkontakte';
    }
}
