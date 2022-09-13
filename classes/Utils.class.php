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
}

?>