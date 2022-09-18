<?php

require_once "includes/autoloader.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teacher-key']))
{
    $teacker_key = Utils::Reveal(Utils::INPUT('teacher-key'));
    $access = isset($_POST['grant-checkbox']) ? 777 : 600;

    if (!empty($teacker_key) && !empty($access)) 
    {
        $table = Constants::$TEACHERS_TABLE;

        $db = Singleton::GetDbHelperInstance();
 
        $upd_sql = "UPDATE $table SET chmod=? WHERE id=?";
        $update = $db -> Pdo -> prepare($upd_sql);
        $update -> execute([$access, $teacker_key]);
        // echo $selectedTeacher . " was assigned to : " . $selected_lrn;
    }  
}
   
Utils::RedirectTo("coordinator.php");

?>