<?php

require_once 'autoloader.inc.php';
require_once 'app-config.php';

class IHttpReferer
{
    /**
     * Check if request is really Ajax request
     */
    public static function IsAjaxRequest() : bool
    {
        return 
        (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        );
    }
    /**
     * Check if Ajax request came from our own Url
     */
    public static function IsRequestOwnUrl($url) : bool
    {
        global $IS_DEBUG;

        $base = (!$IS_DEBUG) ?"http://test-netx.infinityfreeapp.com/aimsdeped/" : 
                              "http://localhost/projects/aimsdeped/"; 
        return 
        (
            !empty($_SERVER['HTTP_REFERER']) && 
            $_SERVER['HTTP_REFERER'] == $base . $url
        );
    }
    /**
     * Generate an ajax token
     */
    public static function GenerateCsrfToken() : string
    {
        // Start session if not started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf-token'] = $token;

        // Generate token expiry date
        $_SESSION['$csrf_token-expiry'] = time() + 3600; // 1hr from now

        return $token;
    }

    /**
     * Check if a csrf token has expired
     */
    public static function IsCsrfExpired() : bool
    {
        if (time() >= $_SESSION['$csrf_token-expiry'])
            return true;

        return false;
    }
}

?>