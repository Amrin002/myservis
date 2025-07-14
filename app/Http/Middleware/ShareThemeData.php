<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareThemeData
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $theme = $user->getTheme();

            // Share theme data with all views
            View::share('currentTheme', $theme);
            View::share('isDarkTheme', $theme === 'dark');
        }

        return $next($request);
    }
}
