<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param string $message
     * @return string[]
     */
    public function success(string $message = 'Success'): array
    {
        return ['success' => $message];
    }

    public function internal(string $message = 'Внутренняя ошибка сервера'): array
    {
        return ['internal' => $message];
    }

    /**
     * @param string $message
     * @return string[]
     */

    public function fail(string $message): array
    {
        return ['fail' => $message];
    }

    public function ok(): JsonResponse
    {
        return response()
            ->json()
            ->setData(['success' => true])
            ->setStatusCode(200);
    }

    public function internalServerError(): JsonResponse
    {
        return response()
            ->json()
            ->setData(['error' => 'Internal server error'])
            ->setStatusCode(500);
    }

    public function forbidden(): JsonResponse
    {
        return response()
            ->json()
            ->setData(['error' => 'Forbidden'])
            ->setStatusCode(403);
    }

    /**
     * @return never
     */

    public function abortExpired(): never
    {
        abort(403, 'Expired');
    }

    /**
     * @return never
     */

    public function abortNotFound(): never
    {
        abort(404, 'Not found');
    }
}
