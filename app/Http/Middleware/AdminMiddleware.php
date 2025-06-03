<?php
// app/Http/Middleware/AdminMiddleware.php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Don't apply middleware to login routes
        if (
            $request->routeIs('login') || $request->routeIs('logout') ||
            $request->is('login') || $request->is('logout')
        ) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect('login');
        }

        if (!Auth::user()->is_admin) {

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }



            return redirect('login')
                ->with('error', 'Access denied. You need administrator privileges.');
        }




        return $next($request);
    }
}
