<?php

require_once "Constants.class.php";

if (session_status() != PHP_SESSION_ACTIVE){
    @session_start();
}

class AuthSession
{
    function __construct()
    {
        date_default_timezone_set("Asia/Manila");
    }

    /**
     * Generate an obfuscated auth token.
     * Auth token is a sequence of random characters
     * with random length from 8 - 16 chars
     */
    public static function GenerateToken() : string
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
    public static function Set(string $username, string $password, string $userid, string $userLevel)
    {
        $authToken = self::GenerateToken();

        $_SESSION[Constants::$SESSION_AUTH_TOKEN] = $authToken; //Utils::Obfuscate($authToken);
        $_SESSION[Constants::$SESSION_AUTH_USERNAME] = $username; // = Utils::Obfuscate($username);
        $_SESSION[Constants::$SESSION_AUTH_PASSWORD] = $password; // Utils::Obfuscate($password);
        $_SESSION[Constants::$SESSION_AUTH_USERID] = $userid; // Utils::Obfuscate($userid); 
        $_SESSION[Constants::$SESSION_AUTH_USER_LEVEL] = $userLevel; // Utils::Obfuscate($userLevel); 
    }

    /**
     * Read the auth token (session) from  if exists.
     * Returns a blank array otherwise
     */
    public static function Load() : array
    {

        if (isset(
            $_SESSION[Constants::$SESSION_AUTH_TOKEN],
            $_SESSION[Constants::$SESSION_AUTH_USERNAME],
            $_SESSION[Constants::$SESSION_AUTH_PASSWORD],
            $_SESSION[Constants::$SESSION_AUTH_USERID],
            $_SESSION[Constants::$SESSION_AUTH_USER_LEVEL]
        ))
        {    
            return 
            [
                "token" => $_SESSION[Constants::$SESSION_AUTH_TOKEN],
                "username" => $_SESSION[Constants::$SESSION_AUTH_USERNAME],
                "password" => $_SESSION[Constants::$SESSION_AUTH_PASSWORD],
                "userid" => $_SESSION[Constants::$SESSION_AUTH_USERID],
                "userlevel" => $_SESSION[Constants::$SESSION_AUTH_USER_LEVEL]
            ];
        }

        return [];
    }

    public static function ClearAuthSession()
    {    
        if (isset(
            $_SESSION[Constants::$SESSION_AUTH_TOKEN],
            $_SESSION[Constants::$SESSION_AUTH_USERNAME],
            $_SESSION[Constants::$SESSION_AUTH_PASSWORD],
            $_SESSION[Constants::$SESSION_AUTH_USERID],
            $_SESSION[Constants::$SESSION_AUTH_USER_LEVEL]
        ))
        {
            unset(
                $_SESSION[Constants::$SESSION_AUTH_TOKEN],
                $_SESSION[Constants::$SESSION_AUTH_USERNAME],
                $_SESSION[Constants::$SESSION_AUTH_PASSWORD],
                $_SESSION[Constants::$SESSION_AUTH_USERID],
                $_SESSION[Constants::$SESSION_AUTH_USER_LEVEL]
            );
        }
    }
}

?>