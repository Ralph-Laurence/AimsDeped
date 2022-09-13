<?php
require_once("autoloader.inc.php"); 

session_start();

// Track for login failures
$login_err = 0; 
$login_err_msg = ""; 
  
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
{ 
    // Input Values
    $username = $_POST['input-username'];
    $password = $_POST['input-password']; 
    
    if (isset($username, $password) && (!empty($username) && !empty($password)))
    {
        try
        {  
            // Check each table if the credential exists there
            
            // The SDO table first




            // $cond = array
            // (
            //     ["student_lrn" => $username],
            //     ["lastname" => $password]
            // );
            // $res = Singleton::GetDbHelperInstance() -> SelectAll_Where("students", $cond, true);


            // $_SESSION["Username"] = $res["username"];
            // $_SESSION["UserRole"] = $res["role"];
            // $_SESSION["UserLevel"] = Utils::GetRoleString($res["role"]); 
 
            // We expect the correct combination of username and password has been found
            if (!empty($res))
            {  
                // $_SESSION["Username"] = $res["student_lrn"]; 
                // $_SESSION["UserLevel"] = "student";

                // Utils::RedirectTo("edit-student-profile.php");
                // Utils::RedirectTo("home.php");
            }
            else
            {
                $login_err++;
                $login_err_msg = "Incorrect username or password"; 
                //exit;
            } 
        }
        catch (Exception $e)
        {
            echo ("Oops something went wrong.<br><br/>" . $e->getMessage());
            exit;
        } 
    }
    else
    {
        $login_err_msg = "Please enter your username and password.";
        exit;
    }
}  
?>