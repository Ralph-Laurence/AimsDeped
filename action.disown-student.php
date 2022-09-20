<?php

include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';
   
$baseUrl = "teacher-landing-page.php";

// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl($baseUrl);
 
if (!$ownUrl) { 
    Utils::RedirectTo("400.php");
} 

session_start();

// Restrict Teacher
if (isset($_SESSION['chmod'])) {
    if ($_SESSION['chmod'] < 777) {
        Utils::RedirectTo("403.php");
    }
}

// Load the login cookie
$authCookie = AuthSession::Load(); // Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("400.php");
    exit;
}

// Make sure that only the teacher can access the system
if ($authCookie[Constants::$SESSION_AUTH_USER_LEVEL] != Constants::$USER_LVL_TEACHER) {
    Utils::RedirectTo("403.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['target_key'], $_POST['owner_key'], $_POST['submit_disown']))
{
    $teacker_key = Utils::Reveal(Utils::INPUT('owner_key'));
    $student_lrn = Utils::Reveal(Utils::INPUT('target_key'));
  
    if ($teacker_key != $authCookie[Constants::$SESSION_AUTH_USERID])
    {
        Utils::RedirectTo("400.php");
        exit;
    }

    if (!empty($teacker_key) && !empty($student_lrn)) 
    {
        $table = Constants::$STUDENTS_TABLE;

        $db = Singleton::GetDbHelperInstance();
 
        $upd_sql = "UPDATE $table SET teacher_id = NULL WHERE student_lrn=?";
        $update = $db -> Pdo -> prepare($upd_sql);
        $update -> execute([$student_lrn]);   
    }  
}
   
Utils::RedirectTo($baseUrl);

?>