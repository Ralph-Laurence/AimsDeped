<?php

class Auth
{
    
    function __construct()
    {
        date_default_timezone_set("Asia/Manila");
    }

    /**
     * Generate an encrypted auth token.
     * Auth token is a sequence of random characters
     * with random length from 8 - 16 chars
     */
    public static function GenerateAuthToken() : string
    {
        $characters = '-_.#@!%$*+=0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_len = rand(8, 16);
        $token = substr(str_shuffle($characters), 0, $rand_len);

        return $token;
    }

    /**
     * Save login information in a browser's cookie.
     * For security purposes, cookies are encrypted.
     * Cookies will expire after 2 days 
     */
    public static function SetAuthCookie(string $username, string $password, string $userid)
    {
        $authToken = self::GenerateAuthToken();

        $expire = strtotime( '+2 days' ); 

        setcookie(Constants::$COOKIE_AUTH_TOKEN_ALIAS,$authToken, $expire,"/");
        setcookie(Constants::$COOKIE_USERNAME_ALIAS, $username, $expire, "/");
        setcookie(Constants::$COOKIE_PASSWORD_ALIAS, $password, $expire, "/");
        setcookie(Constants::$COOKIE_AUTH_USERID_ALIAS, $userid, $expire, "/");
    }

    /**
     * Read the auth token (cookie) from local storage if exists.
     * Returns a blank array otherwise
     */
    public static function LoadAuthCookie() : array
    {

        if (isset(
            $_COOKIE[Constants::$COOKIE_AUTH_TOKEN_ALIAS],
            $_COOKIE[Constants::$COOKIE_USERNAME_ALIAS],
            $_COOKIE[Constants::$COOKIE_PASSWORD_ALIAS],
            $_COOKIE[Constants::$COOKIE_AUTH_USERID_ALIAS]
        ))
        {    
            return 
            [
                "token" => $_COOKIE[Constants::$COOKIE_AUTH_TOKEN_ALIAS],
                "username" => $_COOKIE[Constants::$COOKIE_USERNAME_ALIAS],
                "password" => $_COOKIE[Constants::$COOKIE_PASSWORD_ALIAS],
                "userid" => $_COOKIE[Constants::$COOKIE_AUTH_USERID_ALIAS]
            ];
        }

        return [];
    }

    public static function ClearAuthCookie()
    {    
        if (isset($_COOKIE[Constants::$COOKIE_AUTH_TOKEN_ALIAS], $_COOKIE[Constants::$COOKIE_USERNAME_ALIAS], $_COOKIE[Constants::$COOKIE_PASSWORD_ALIAS]))
        {  
            setcookie(Constants::$COOKIE_AUTH_TOKEN_ALIAS, '', 1, '/');
            setcookie(Constants::$COOKIE_USERNAME_ALIAS, '', 1, '/');
            setcookie(Constants::$COOKIE_PASSWORD_ALIAS, '', 1, '/');
            setcookie(Constants::$COOKIE_AUTH_USERID_ALIAS, '', 1, '/');

            unset
            (
                $_COOKIE[Constants::$COOKIE_AUTH_TOKEN_ALIAS],
                $_COOKIE[Constants::$COOKIE_USERNAME_ALIAS],
                $_COOKIE[Constants::$COOKIE_PASSWORD_ALIAS],
                $_COOKIE[Constants::$COOKIE_AUTH_USERID_ALIAS]
            ); 
        }
    }
}
