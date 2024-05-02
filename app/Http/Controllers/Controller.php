<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function internalServerErrorResponse(): JsonResponse
    {
        return response()->json()
            ->setData(['error' => 'Internal server error'])
            ->setStatusCode(500);
    }

    public function forbiddenResponse(): JsonResponse
    {
        return response()->json()
            ->setData(['error' => 'Forbidden'])
            ->setStatusCode(403);
    }
}
