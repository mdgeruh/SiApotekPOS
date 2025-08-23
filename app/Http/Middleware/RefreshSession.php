<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Helpers\CsrfHelper;

class RefreshSession
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
        // Update last activity
        CsrfHelper::updateLastActivity();

        // Check if session is expired
        if (CsrfHelper::isSessionExpired()) {
            // Regenerate session (this also regenerates CSRF token)
            CsrfHelper::refreshSession();

            // Add flash message
            Session::flash('warning', 'Session telah diperbarui. Silakan coba lagi.');
        }

        // Add CSRF token to all views
        view()->share('csrf_token', CsrfHelper::getToken());

        // Ensure Livewire session is stable
        if ($request->hasHeader('X-Livewire')) {
            // Don't regenerate session for Livewire requests
            return $next($request);
        }

        return $next($request);
    }
}
