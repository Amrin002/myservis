<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Middleware untuk guest Tuser (belum login)
class TuserRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('tuser')->check()) {
            // Redirect to tuser dashboard if already authenticated
            return redirect()->route('tuser.tuser.dashboard');
        }

        return $next($request);
    }
}
