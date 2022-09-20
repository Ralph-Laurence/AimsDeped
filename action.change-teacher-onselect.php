<?php

require_once "includes/autoloader.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select-teacher'], $_POST['student-row']))
{
    $selectedTeacher = Utils::Reveal(Utils::INPUT('select-teacher'));
    $selected_lrn = Utils::Reveal(Utils::INPUT("student-row"));

    if (!empty($selectedTeacher) && !empty($selected_lrn)) 
    {
        $handles = Constants::$TEACHER_HANDLES_TABLE;
        $db = Singleton::GetDbHelperInstance();
 
        $student_table = Constants::$STUDENTS_TABLE;

        $upd_sql = "UPDATE $student_table SET teacher_id=? WHERE student_lrn=?";
        $update = $db -> Pdo -> prepare($upd_sql);
        $update -> execute([$selectedTeacher, $selected_lrn]);
    }  
}
   
Utils::RedirectTo("teacher-landing-page.php");

?>