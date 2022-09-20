<?php

//#########################################
// STUDENT MANAGEMENT FROM TEACHER's VIEW
//#########################################

require_once "autoloader.inc.php";

$db = Singleton::GetDbHelperInstance();

$table = Constants::$STUDENTS_TABLE;

$entriesPerPage = 10;
$totalEntries = $db -> CountRows($table);                          
$totalPages = ceil($totalEntries / $entriesPerPage);       // Pagination count
 
$currentPageIndex = isset($_POST['page-index']) ? htmlspecialchars(Utils::INPUT('page-index')) : 1; // isset($_GET['page']) ? $_GET['page'] : 1;

$offsetX = ($currentPageIndex - 1) * $entriesPerPage;

$x = $offsetX;
if ($x < 1)
    $x = 0;

$handles_table = Constants::$TEACHER_HANDLES_TABLE;
$teacher_table = Constants::$TEACHERS_TABLE;
  
$filter_mode = htmlspecialchars(Utils::INPUT("filter_students"));
$teacher_id = htmlspecialchars(Utils::Reveal(Utils::INPUT("filter_key")));

$has_filter = (!empty($filter_mode) && $filter_mode == "mine");
$filter = ($has_filter) ? "WHERE t.id=:teacherId" : "";

$sql ="SELECT 
s.student_lrn AS 'LRN',
CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS 'StudentName', 
CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'TeacherInCharge',
t.id AS 'TeachersId'
FROM $table s 

LEFT JOIN $teacher_table t ON s.teacher_id = t.id
$filter
ORDER BY s.student_lrn
LIMIT :x, :y";

$sth = $db -> Pdo -> prepare($sql); 
$sth -> bindValue(":x", (int)$x, PDO::PARAM_INT);
$sth -> bindValue(":y", (int)$entriesPerPage, PDO::PARAM_INT);

if ($has_filter) {
    $sth -> bindValue(":teacherId", $teacher_id);
}

$sth -> execute();

$students_table = $sth -> fetchAll(PDO::FETCH_ASSOC);

?>