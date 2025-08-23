<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\AppSettingHelper;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if maintenance mode is enabled
        if (AppSettingHelper::isMaintenanceMode()) {
            // Allow admin users to access during maintenance
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                return $next($request);
            }

            // Show maintenance page for other users
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Aplikasi sedang dalam mode maintenance. Silakan coba lagi nanti.',
                    'maintenance_mode' => true
                ], 503);
            }

            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
