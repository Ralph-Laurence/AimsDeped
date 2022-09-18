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
 
$currentPageIndex = isset($_GET['page']) ? $_GET['page'] : 1;

$offsetX = ($currentPageIndex - 1) * $entriesPerPage;

// $students_table = $db -> SelectLimitOffset($table, $offsetX, $entriesPerPage);
$x = $offsetX;
if ($x < 1)
    $x = 0;

$handles_table = Constants::$TEACHER_HANDLES_TABLE;
$teacher_table = Constants::$TEACHERS_TABLE;
// $sql = "SELECT 
// s.id,
// s.student_lrn AS 'LRN',
// CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS 'StudentName',
// g.level AS 'GradeSection',
// CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'TeacherInCharge',
// t.id AS 'TeachersId'
// FROM `students` s 
// LEFT JOIN $handles h on h.section_id = s.section_id
// LEFT JOIN teachers t ON t.id = h.teacher_id
// LEFT JOIN grade_section g ON g.id = h.section_id
// ORDER BY s.student_lrn
// LIMIT :x, :y";

$sql ="SELECT 
s.student_lrn AS 'LRN',
CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS 'StudentName', 
CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'TeacherInCharge',
t.id AS 'TeachersId'
FROM `students` s 

LEFT JOIN `teachers` t ON s.teacher_id = t.id
ORDER BY s.student_lrn
LIMIT :x, :y";

// LEFT JOIN `teacher_student_handles` h ON s.teacher_id = h.teacher_id 

$sth = $db -> Pdo -> prepare($sql); 
$sth -> bindValue(":x", (int)$x, PDO::PARAM_INT);
$sth -> bindValue(":y", (int)$entriesPerPage, PDO::PARAM_INT);
$sth -> execute();

$students_table = $sth -> fetchAll(PDO::FETCH_ASSOC);

?>