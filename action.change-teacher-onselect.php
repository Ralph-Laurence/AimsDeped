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

        // delete existing assigns
        // $del_sql = "DELETE FROM $handles WHERE teacher_id=? AND student_lrn=?";
        // $del = $db->Pdo->prepare($del_sql);
        // $del -> execute([$selectedTeacher, $selected_lrn]);

        //$upd_sql = "INSERT INTO $handles (teacher_id, student_lrn) VALUES (?,?)";
        $student_table = Constants::$STUDENTS_TABLE;

        $upd_sql = "UPDATE $student_table SET teacher_id=? WHERE student_lrn=?";
        $update = $db -> Pdo -> prepare($upd_sql);
        $update -> execute([$selectedTeacher, $selected_lrn]);
        // echo $selectedTeacher . " was assigned to : " . $selected_lrn;
    }  
}
   
Utils::RedirectTo("teacher-landing-page.php");

?>