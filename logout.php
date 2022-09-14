<?php
require_once("includes/autoloader.inc.php");

Auth::ClearAuthCookie();
 
session_start();
unset(
    $_SESSION["Username"],
    $_SESSION["UserRole"], 
    $_SESSION["UserLevel"],
    $_SESSION["IsLoggedIn"]
);
 
session_destroy();

header('Location: login.php');
?>