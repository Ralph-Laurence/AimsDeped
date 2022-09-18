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
            $credential = array(
                "username" => $username,
                "password" => $password,
            );

            $student = array(
                "student_lrn" => $username,
                "password" => $password
            );

            $db = Singleton::GetDbHelperInstance();

            // We use this to track which user is currently logging in.
            // In this case,
            // 0 -> SDO
            // 1 -> Coord
            // 2 -> Teacher
            // 3 -> Student

            $flag_userType = -1; 

            // We will use this to save the record id for future usage
            $flag_userId = -1;

            // We will use this to check if the teacher can access the system
            $chmod = 0;

            // The SDO table first
            $sdo_table = $db -> SelectRow_Where(Constants::$SDO_TABLE, $credential, true);

            if (!empty($sdo_table)) {
                $flag_userType = 0; 
                $flag_userId = $sdo_table["id"];
            }

            // The COORDINATORS table
            $coord_table = $db -> SelectRow_Where(Constants::$COORD_TABLE, $credential, true);

            if (!empty($coord_table)) {
                $flag_userType = 1; 
                $flag_userId = $coord_table["id"];
            }

            // The TEACHERS table
            $teachers_table = $db -> SelectRow_Where(Constants::$TEACHERS_TABLE, $credential, true);

            if (!empty($teachers_table)) {
                $flag_userType = 2; 
                $flag_userId = $teachers_table["id"];
                $chmod = $teachers_table['chmod'];
            } 
            
            // The STUDENTS table
            $students_table = $db -> SelectRow_Where(Constants::$STUDENTS_TABLE, $student, true);

            if (!empty($students_table)) { 
                $flag_userType = 3;
                $flag_userId = $students_table["id"];
            }

            // echo "<br>Usertype: " . $flag_userType . "<br>";

            // (THIS APPROACH IS BUGGY. What if may same na credentials pero nasa ibang tables?)

            // Generate login cookie then save to local (browser's storage) for later usage
            // We only generate cookie if login is successfull
            if ($flag_userType > -1)
            {
                Auth::SetAuthCookie($username, $password, $flag_userId);

                // Identify which page should we redirect to
                switch($flag_userType)
                {
                    case 0:
                        Utils::RedirectTo("sdoadmin.php");
                        break;
                    case 1:
                        Utils::RedirectTo("coordinator.php");
                        break;
                    case 2:
                        if ($chmod > 600)
                        {
                            Utils::RedirectTo("teacher-landing-page.php");
                        }
                        else
                        {
                            Utils::RedirectTo("403.php");
                        }
                        break;
                    case 3:
                        Utils::RedirectTo("student-profile.php");
                        break;
                    default:
                        Utils::RedirectTo("404.php");
                        break; 
                } 
                exit;
            }
                //exit;
 

            $login_err++;
            $login_err_msg = "Incorrect username or password";
             
        } catch (Exception $e) {
            echo ("Oops something went wrong.<br><br/>" . $e->getMessage());
            exit;
        }
    } else {
        $login_err_msg = "Please enter your username and password.";
        exit;
    }
}
