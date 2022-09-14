<?php
 
class Config
{
    private static string $server_host = "sql104.epizy.com"; 
    private static string $server_username = "epiz_31851432";
    private static string $server_password = "EXpPZWg8NwHXb";
    private static string $server_db = "epiz_31851432_deped";
     
    private static string $local_host = "localhost"; 
    private static string $local_username = "root";
    private static string $local_password = "";
    private static string $local_db = "deped";
 
    public static function GetHost() : string
    {
        return self::IsLocalhost() ? self::$local_host : self::$server_host;
    }

    public static function GetUsername() : string
    {
        return self::IsLocalhost() ? self::$local_username : self::$server_username;
    }

    public static function GetPassword() : string
    {
        return self::IsLocalhost() ? self::$local_password : self::$server_password;
    }

    public static function GetDbName() : string
    {
        return self::IsLocalhost() ? self::$local_db : self::$server_db;
    }

    public static function IsLocalhost($whitelist = ['127.0.0.1', '::1']) 
    {
        return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
    }
}

?>