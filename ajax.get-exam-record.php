<?php
include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';
  
// CHECK IF REQUEST IS REALLY AJAX
$isAjax = IHttpReferer::IsAjaxRequest();
  
// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl("teacher-selected-student.php");

$legitRequest = $isAjax && $ownUrl;

if (!$legitRequest) 
{
    WriteResponse("Ajax URL not found or invalid");
    Utils::RedirectTo("404.php");
} 
 
//######################################
// REGION: TEACHER's INFORMATION
//######################################
// Load the login cookie
$authCookie = Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
} 

$lrn_key = Utils::INPUT("input_key");

$exam_query = "SELECT 

x.record_title AS 'title',
x.record_date AS 'date',
x.score AS 'score',
x.remarks AS 'remarks' 

FROM `exam_record_sheet` x 
left JOIN teachers t on t.id = x.teacher_id 
left join students s on s.student_lrn = x.student_lrn 
where x.teacher_id = ? and x.student_lrn = ?";

$db = Singleton::GetDbHelperInstance();

$sth = $db->Pdo->prepare($exam_query);
$sth->execute([$authCookie["userid"], Utils::Reveal($lrn_key)]);
$exam_result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo "xxx";

?>