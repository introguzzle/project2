<?php

namespace App\Http\Middleware;

use App\Utils\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $identity = Auth::getIdentity();

        if (!$identity || (($identity->email !== null) !== $identity->hasVerifiedEmail()))  {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
