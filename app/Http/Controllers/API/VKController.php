<?php

namespace App\Http\Controllers\API;

class VKController extends SocialAuthController
{
    public function getDriverName(): string
    {
        return 'vkontakte';
    }
}
