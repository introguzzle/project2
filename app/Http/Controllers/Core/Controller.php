<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

abstract class Controller extends \Illuminate\Routing\Controller
{

    /**
     * @param string $message
     * @return string[]
     */
    public function success(
        string $message = 'Success'
    ): array
    {
        return ['success' => $message];
    }

    public function notification(
        string $message = 'Notification'
    ): array
    {
        return ['notification' => $message];
    }

    public function internal(
        string $message = 'Внутренняя ошибка сервера'
    ): array
    {
        return ['internal' => $message];
    }

    /**
     * @param string $message
     * @return string[]
     */

    public function fail(
        string $message
    ): array
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

    public function internalServerErrorResponse(
        string $message = 'Internal server error'
    ): JsonResponse
    {
        return response()
            ->json()
            ->setData(['error' => $message])
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

    public function back(): RedirectResponse
    {
        return redirect()->back()->withInput();
    }

    public function unsupported(): RedirectResponse
    {
        return $this->back()->with($this->internal('Эта функция пока не поддерживается'));
    }
}
