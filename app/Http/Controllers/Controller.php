<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public const string INTERNAL_ERROR_MESSAGE = 'Внутренняя ошибка сервера';
    protected array $internal = ['internal' => self::INTERNAL_ERROR_MESSAGE];

    /**
     * @param string $message
     * @return string[]
     */
    public function success(string $message = 'Success'): array
    {
        return ['success' => $message];
    }

    /**
     * @param string $message
     * @return string[]
     */

    public function fail(string $message): array
    {
        return ['fail' => $message];
    }

    public function internalServerErrorResponse(): JsonResponse
    {
        return response()
            ->json()
            ->setData(['error' => 'Internal server error'])
            ->setStatusCode(500);
    }

    public function forbiddenResponse(): JsonResponse
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
