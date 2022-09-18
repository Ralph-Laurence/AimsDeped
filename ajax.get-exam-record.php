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

$lrn = Utils::Reveal(Utils::INPUT("input_key"));
$teacher_id = Utils::Reveal(Utils::INPUT("user_key"));


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
$sth->execute([$teacher_id, $lrn]);
$exam_result = $sth->fetchAll(PDO::FETCH_ASSOC);
 
$result_sets = "";
 
foreach ($exam_result as $res) 
{
    $date = Utils::DateFmt($res['date'], "F-d-Y");

    $data = "<tr> 
                    <th scope=\"row\">{$res['title']}</th>
                    <th>{$res['score']}</th>
                    <th>{$date}</th>
                    <th>{$res['remarks']}</th>
                </tr>";

    $result_sets .= $data;
}  

header('Content-Type: application/json');
echo json_encode($result_sets, true);
exit;

//echo $result_sets;
// header('Content-Type: application/json');
// echo json_encode($result_sets, true);
// exit;
//WriteResponse($response_result_sets);

// function WriteResponse($msg)
// {
//     // Store responses here
//     $response_result_sets = array(
//         //"new-token" => IHttpReferer::GenerateCsrfToken(), // refresh new csrf token
//         "response" => $msg
//     );

//     // We will return json as response
//     header('Content-Type: application/json');
//     echo json_encode($response_result_sets, true);
//     exit();
// }
?>