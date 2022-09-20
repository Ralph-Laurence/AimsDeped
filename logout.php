<?php
require_once("includes/autoloader.inc.php");

Auth::ClearAuthCookie();
AuthSession::ClearAuthSession();
 
session_start();
unset(
    $_SESSION["Username"],
    $_SESSION["UserRole"], 
    $_SESSION["UserLevel"],
    $_SESSION["IsLoggedIn"],
    $_SESSION['login_request'],
    $_SESSION['chmod'],
);
 
session_destroy();

header('Location: login.php');
?>