<?php

include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';

// CHECK IF REQUEST IS REALLY AJAX
$isAjax = IHttpReferer::IsAjaxRequest();
  
// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl("ajax.php");

$legitRequest = $isAjax && $ownUrl;

if (!$legitRequest) {
    Utils::RedirectTo("404.php");
} 

session_start();

$csrf_token = 'csrf-token';

// Token must be set
if (!isset($_POST[$csrf_token]) || (!isset($_SESSION[$csrf_token])))
{
    exit("Token not found");
}
// Validate token
if (Utils::INPUT($csrf_token) != $_SESSION[$csrf_token])
{
    exit("Invalid token");
}
// Check if csrf token has not expired yet
if (IHttpReferer::IsCsrfExpired())
{
    exit("Token has expired. Please reload the page.");
}

// We expect all token to be set and valid
// Unregister the tokens
unset($_SESSION[$csrf_token]);
unset($_SESSION['$csrf_token-expiry']);


// Store responses here
$response_result_sets = array(
    "new-token" => IHttpReferer::GenerateCsrfToken(), // refresh new csrf token
);

// We will return json as response
header('Content-Type: application/json');
echo json_encode($response_result_sets, true);
exit();
?>