<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMobileDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ignore API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Already on mobile routes
        if ($request->is('mobile/*')) {
            return $next($request);
        }

        // Detect mobile device
        $isMobile = preg_match(
            '/android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i',
            $request->header('User-Agent')
        );

        if ($isMobile && auth()->check() && auth()->user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($isMobile) {
            // If not logged in → mobile login
            if (!auth()->check()) {
                return redirect()->route('mobile.login');
            }

            // If logged in delivery boy → mobile dashboard
            if (auth()->user()->role === 'delivery_boy') {
                return redirect()->route('mobile.dashboard');
            }
        }

        return $next($request);
    }
}
