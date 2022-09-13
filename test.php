<?php
require_once("includes/autoloader.inc.php");

$doc = Singleton::GetBoilerPlateInstance();
$doc -> BeginHTML();

$db = Singleton::GetDbHelperInstance();

$cond = array("lastname" => "Osmena");
$select = $db -> SelectRowWhere("students", $cond);

if (!$db -> IsRowEmpty($select))
{
    echo $select["student_lrn"];
    exit;
}

echo "select is empty";

$doc -> EndHTML();
?>