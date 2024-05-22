<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GoogleController extends SocialAuthController
{
    public function getDriverName(): string
    {
        return 'google';
    }
}
