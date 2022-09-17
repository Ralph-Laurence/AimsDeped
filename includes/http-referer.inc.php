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
}

?>