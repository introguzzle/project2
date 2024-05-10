<?php

namespace App\Http\Middleware;

use App\Utils\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $profile = Auth::getProfile();

        if ($profile === null) {
            return redirect('login');
        }

        if (!$profile->isAdmin()) {
            return redirect('login');
        }

        return $next($request);
    }
}
