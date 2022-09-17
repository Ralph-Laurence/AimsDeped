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

    public static function DateFmt($date, $format) : string
    {
        $date = new DateTime($date);
        $result = $date -> format($format);
        return $result;
    }

    /**
     * 2 - Layer obfuscation
     */
    public static function Obfuscate($raw) : string
    {
        // Raw to BASE 64
        $l1 = urlencode(base64_encode($raw));

        // Base64 to Bin2Hex
        $l2 = bin2hex($l1); 

        return $l2;
    }

    /**
     * Reveal Obfuscated string
     */
    public static function Reveal($obfuscated) : string
    { 
        // Bin2Hex to Base64
        $r2 = hex2bin($obfuscated); 

        $r1 = base64_decode(urldecode($r2));
 
        return $r1;
    }
}

?>