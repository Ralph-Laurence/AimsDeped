<?php

class Utils
{
    //
    // SAFE REDIRECT
    //
    public static function RedirectTo(string $url)
    {
        // If no headers are sent, send one
        if (!headers_sent()) 
        {
            // Check if using cookies
            if (strlen(session_id()) > 0) 
            {
                session_regenerate_id(); // Prevent session fixation attacks
                session_write_close(); // Prevent session from locking other request
            }

            header("Location: " . $url);
        } 
        exit;
    }
    //
    // SAFE POST
    //
    public static function INPUT($input) : string
    {
        if (isset($_POST[$input]))
        {
            return $_POST[$input];
        }

        return "";
    }
    //
    // Find array key with empty value
    //
    public static function GetEmptyKeyInArray(array $arr) : string
    {
        foreach ($arr as $k => $v)
        {
            if (empty($v))
            {
                echo $k . " is empty";
                return $k;
            }
        }

        return "";
    }
}

?>