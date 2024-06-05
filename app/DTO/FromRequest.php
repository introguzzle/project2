<?php

namespace App\DTO;

use App\Other\Requests;
use Illuminate\Http\Request;

trait FromRequest
{
    public static function fromRequest(Request $request): static
    {
        return Requests::compact($request, static::class);
    }
}
