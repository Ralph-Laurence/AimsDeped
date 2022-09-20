<?php

//#########################################
// STUDENT MANAGEMENT FROM TEACHER's VIEW
//#########################################

require_once "autoloader.inc.php";

$db = Singleton::GetDbHelperInstance();

//
// PAGINATION LOGIC
//
$handles_table = Constants::$TEACHER_HANDLES_TABLE;
$teacher_table = Constants::$TEACHERS_TABLE;
$schools_table = Constants::$SCHOOLS_TABLE;
$pupils_table = Constants::$STUDENTS_TABLE;

$filter_mode = htmlspecialchars(Utils::INPUT("filter_students"));
$teacher_id = htmlspecialchars(Utils::Reveal(Utils::INPUT("filter_key")));

$has_filter = (!empty($filter_mode) && $filter_mode == "mine");
$filter = ($has_filter) ? "AND t.id=:teacherId" : "";

$students_in_current_school = "SELECT 
COUNT(s.student_lrn) AS 'total'
FROM $pupils_table s 

LEFT JOIN $teacher_table t ON s.teacher_id = t.id
LEFT JOIN $schools_table b ON b.school_id = s.school_assigned
WHERE b.school_id = t.school_assigned $filter";

$sth_total_students = $db -> Pdo -> prepare($students_in_current_school);
if ($has_filter) {
    $sth_total_students ->bindValue(':teacherId', $teacher_id);
} 
$sth_total_students -> execute(); 
$total_students_in_current_school = $sth_total_students->fetchColumn();

$entriesPerPage = 10;
$totalEntries = $total_students_in_current_school; //$db -> CountRows($pupils_table); //($students_table);                          
$totalPages = ceil($totalEntries / $entriesPerPage);
// $totalEntries = $db -> CountRows($table);                          
// $totalPages = ceil($totalEntries / $entriesPerPage);       // Pagination count
 
$currentPageIndex = isset($_POST['page-index']) ? htmlspecialchars(Utils::INPUT('page-index')) : 1; // isset($_GET['page']) ? $_GET['page'] : 1;

$offsetX = ($currentPageIndex - 1) * $entriesPerPage;

$x = $offsetX;
if ($x < 1)
    $x = 0;
  
$sql ="SELECT 
s.student_lrn AS 'LRN',
CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS 'StudentName', 
CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'TeacherInCharge',
t.id AS 'TeachersId'
FROM $pupils_table s 

LEFT JOIN $teacher_table t ON s.teacher_id = t.id
LEFT JOIN schools b ON b.school_id = s.school_assigned
WHERE b.school_id = t.school_assigned $filter
ORDER BY s.student_lrn
LIMIT :x, :y";

$sth = $db -> Pdo -> prepare($sql); 
$sth -> bindValue(":x", (int)$x, PDO::PARAM_INT);
$sth -> bindValue(":y", (int)$entriesPerPage, PDO::PARAM_INT);

if ($has_filter) {
    $sth -> bindValue(":teacherId", $teacher_id);
}

$sth -> execute();

$students_table = $sth -> fetchAll(PDO::FETCH_ASSOC) ?: [];

$total_students_result = count($students_table);
 
?>