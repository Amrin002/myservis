<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TuserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('tuser')->check()) {
            // Redirect to tuser login page if not authenticated
            return redirect()->route('tuser.login');
        }

        // Check if tuser account is active
        if (Auth::guard('tuser')->user()->status !== 'active') {
            Auth::guard('tuser')->logout();
            return redirect()->route('tuser.login')
                ->withErrors(['status' => 'Akun Anda tidak aktif.']);
        }

        return $next($request);
    }
}
