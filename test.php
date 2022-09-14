<?php
require_once("includes/autoloader.inc.php");

$doc = Singleton::GetBoilerPlateInstance();
$doc -> BeginHTML();

$db = Singleton::GetDbHelperInstance();

$cond = array("lastname" => "Osmena", "student_lrn" => "123");
 
$select = $db -> SelectRow_Where("students", $cond);

if (!$db -> IsResultSetEmpty($select))
{
    echo $select["student_lrn"];
    exit;
}

echo "select is empty";

$doc -> EndHTML();
?>