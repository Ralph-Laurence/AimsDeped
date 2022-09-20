<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function CheckIfLoggedIn()
{
    if (isset($_SESSION['login_request']) && $_SESSION['login_request'] > -1) {
        switch ($_SESSION['login_request']) {
            case 0:
                Utils::RedirectTo("sdoadmin.php");
                break;
            case 1:
                Utils::RedirectTo("coordinator.php");
                break;
            case 2:
                Utils::RedirectTo("teacher-landing-page.php");
                break;
            case 3:
                Utils::RedirectTo("student-profile.php");
                break;
            default:
                Utils::RedirectTo("login.php");
                break;
        }
    }
}

?>