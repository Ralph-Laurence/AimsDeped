<?php

//#########################################
// STUDENT MANAGEMENT FROM TEACHER's VIEW
//#########################################

require_once "includes/autoloader.inc.php";

$db = Singleton::GetDbHelperInstance();

$table = Constants::$STUDENTS_TABLE;

$entriesPerPage = 10;
$totalEntries = $db -> CountRows($table);                          
$totalPages = ceil($totalEntries / $entriesPerPage);       // Pagination count
 
$currentPageIndex = isset($_GET['page']) ? $_GET['page'] : 1;

$offsetX = ($currentPageIndex - 1) * $entriesPerPage;

$students_table = $db -> SelectLimitOffset($table, $offsetX, $entriesPerPage);

?>