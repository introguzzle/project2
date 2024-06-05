<?php

namespace App\Http\Controllers\API\Auth;

class GoogleController extends Controller
{
    public function getDriverName(): string
    {
        return 'google';
    }
}
