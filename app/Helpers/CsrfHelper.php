<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CsrfHelper
{
    /**
     * Regenerate CSRF token
     */
    public static function regenerateToken()
    {
        Session::regenerateToken();
        return Session::token();
    }

    /**
     * Check if CSRF token is valid
     */
    public static function isValidToken($token)
    {
        return Session::token() === $token;
    }

    /**
     * Get current CSRF token
     */
    public static function getToken()
    {
        return Session::token();
    }

    /**
     * Refresh session
     */
    public static function refreshSession()
    {
        // Regenerate session (this also regenerates CSRF token)
        Session::regenerate();
        
        // Ensure CSRF token is available
        if (!Session::has('_token')) {
            Session::regenerateToken();
        }
        
        return true;
    }

    /**
     * Check if session is expired
     */
    public static function isSessionExpired()
    {
        $lastActivity = Session::get('last_activity');
        $lifetime = config('session.lifetime', 120);

        if (!$lastActivity) {
            return true;
        }

        $expired = time() - $lastActivity > ($lifetime * 60);
        return $expired;
    }

    /**
     * Update last activity
     */
    public static function updateLastActivity()
    {
        Session::put('last_activity', time());
        return true;
    }

    /**
     * Clear expired sessions
     */
    public static function clearExpiredSessions()
    {
        $sessionPath = storage_path('framework/sessions');

        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/*');
            $now = time();

            foreach ($files as $file) {
                if (is_file($file)) {
                    if ($now - filemtime($file) >= config('session.lifetime', 120) * 60) {
                        unlink($file);
                    }
                }
            }
        }

        return true;
    }
}
