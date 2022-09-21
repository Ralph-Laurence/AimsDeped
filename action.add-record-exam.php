<?php
include_once 'includes/autoloader.inc.php';
require_once 'includes/http-referer.inc.php';
   
@session_start();

$referrer = "view-student.php";
// Only give response when request came from our own url
$ownUrl = IHttpReferer::IsRequestOwnUrl($referrer);
 
if (!$ownUrl) 
{
    // WriteResponse("Ajax URL not found or invalid");
    Utils::RedirectTo("404.php");
} 
  
$authCookie = AuthSession::Load(); //Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
}
  
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher_key'], $_POST['student_key'], $_POST['school_key']))
{ 
    $db = Singleton::GetDbHelperInstance();

    $lrn = Utils::Reveal(Utils::INPUT("student_key"));
    $teacher_id = Utils::Reveal(Utils::INPUT('teacher_key'));
    $school_id = Utils::Reveal(Utils::INPUT("school_key"));
    
    $required_fields = array
    ( 
        "record_title" => htmlspecialchars(Utils::INPUT("input_exam_title")), 
        "record_date" => htmlspecialchars(Utils::INPUT("input_exam_date")),
        "score" => htmlspecialchars(Utils::INPUT("input_score")),  
        "total_items" => htmlspecialchars(Utils::INPUT("input_total_items")),  
        "proficiency_rating" => htmlspecialchars(Utils::INPUT("input_rating")), 
        "student_lrn" => htmlspecialchars($lrn),
        "teacher_id" => htmlspecialchars($teacher_id), 
        "school_assigned" => htmlspecialchars($school_id),
        "last_modified_by" => htmlspecialchars($teacher_id) 
    );

    $hasEmptyValues = Utils::GetEmptyKeyInArray($required_fields);

    if ($hasEmptyValues) {
        Utils::RedirectTo("400.php");
        exit;
    }

    // Load teacher info from db
    // We will use this to get the complete name
    // whenever the input field for 'conducted_by' is empty, 
    // then we can automatically set this teacher's name for that field
    $sql_teacher_info = "SELECT  
    CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'teacher_name'
    FROM teachers t 
    left join schools s ON t.school_assigned = s.school_id
    WHERE t.id =?";

    $sth_teacher_info = $db->Pdo->prepare($sql_teacher_info);
    $sth_teacher_info->execute([$authCookie["userid"]]);
    $teacher_info = $sth_teacher_info->fetch(PDO::FETCH_ASSOC) ?: [];

    if (empty($teacher_info)) {
        // Failed to load teacher's data
        Utils::RedirectTo("400.php");
        exit;
    }

    // Optional fields
    $required_fields["remarks"] = htmlspecialchars(Utils::INPUT("input_remarks"));
    $required_fields["conducted_by"] = htmlspecialchars(Utils::INPUT("input_conducted_by")) ?: $teacher_info["teacher_name"];
 
    $record_query = $db->InsertRow(Constants::$EXAM_RECORDS_TABLE, $required_fields);

    $_SESSION["action_add_exam_record_msg"] = "success";

    if (!$record_query) {
        $_SESSION["action_add_exam_record_msg"] = "fail";
    }   
}
 
Utils::RedirectTo($referrer); 
?>