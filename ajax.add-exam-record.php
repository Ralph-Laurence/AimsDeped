<?php

include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';
  
// CHECK IF REQUEST IS REALLY AJAX
$isAjax = IHttpReferer::IsAjaxRequest();
  
// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl("teacher-view-student.php");

$legitRequest = $isAjax && $ownUrl;

if (!$legitRequest) 
{
    WriteResponse("Ajax URL not found or invalid");
    Utils::RedirectTo("404.php");
} 

session_start();

$csrf_token = 'csrf-token';

// Token must be set
if (!isset($_POST[$csrf_token]) || (!isset($_SESSION[$csrf_token])))
{
    WriteResponse("Token not found");
    exit;
}
// Validate token
if (Utils::INPUT($csrf_token) != $_SESSION[$csrf_token])
{
    WriteResponse("Invalid token");
    exit;
}
// Check if csrf token has not expired yet
if (IHttpReferer::IsCsrfExpired())
{
    WriteResponse("Token has expired. Please reload the page.");
    exit;
}

// We expect all token to be set and valid
// Unregister the tokens
unset($_SESSION[$csrf_token]);
unset($_SESSION['$csrf_token-expiry']);

//------------------------------------------------
// BEGIN :
// Record the student's exam data
//------------------------------------------------

// These are requried fields.
$exam_data = array(
 
    "record_title" => htmlspecialchars(Utils::INPUT("record_title")), 
    "record_date" => htmlspecialchars(Utils::INPUT("date_taken")),
    "score" => htmlspecialchars(Utils::INPUT("score")),  
    "student_lrn" => Utils::Reveal(Utils::INPUT("input_key")),
    "teacher_id" => Utils::Reveal(Utils::INPUT("user_key")),
    //"proficiency" => ,  
); 
//
// Make sure the form has no empty values
//
$hasEmptyValues = Utils::GetEmptyKeyInArray($exam_data);
    
if ($hasEmptyValues)
{  
    WriteResponse("Submit failed! The required fields are incomplete or you may have entered an invalid value. Please try again.");
    exit;
}

// Optional Fields
$exam_data["remarks"] = htmlspecialchars(Utils::INPUT("remarks"));

$db = Singleton::GetDbHelperInstance();
$insert = $db -> InsertRow(Constants::$EXAM_RECORDS_TABLE, $exam_data);

$resp_msg = (!$insert) ? "Failed to record data." : "Record successfully added!";

WriteResponse($resp_msg);
 
function WriteResponse($msg)
{
    // Store responses here
    $response_result_sets = array(
        "new-token" => IHttpReferer::GenerateCsrfToken(), // refresh new csrf token
        "response" => $msg
    );

    // We will return json as response
    header('Content-Type: application/json');
    echo json_encode($response_result_sets, true);
    exit();
}
?>