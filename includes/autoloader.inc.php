<?php
/**
 * THIS MODULE WILL AUTO-INCLUDE ALL CLASSES UNDER THE 
 * 'classes' FOLDER
 */

spl_autoload_register("register");

function register($className)
{   
    // CLASSES FOLDER
    $className = str_replace("\\", "/", $className);
    $fullPath = "classes/" . $className . ".class.php"; 

    if (!file_exists($fullPath))
    {
        //return false;
        echo "Searching for class under: " . $fullPath . "<br>";
	    echo $className . " does not exist";
    }
    require_once $fullPath; 
} 

?>