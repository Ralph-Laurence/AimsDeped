<?php

require_once "autoloader.inc.php";
  
$errCount = 0;
$errMsg = "";

$successCount = 0;
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
{
    $required_fields = array(
        "username"  => htmlspecialchars(Utils::INPUT('username')),
        "firstname" => htmlspecialchars(Utils::INPUT('fname')),
        "middlename" => htmlspecialchars(Utils::INPUT('mname')),
        "lastname" => htmlspecialchars(Utils::INPUT('lname')),
        //"password" => htmlspecialchars(Utils::INPUT('')),
        "email" => htmlspecialchars(Utils::INPUT('emailAddress')),
        "contact" => htmlspecialchars(Utils::INPUT('contact')),
        "school_assigned" => htmlspecialchars(Utils::INPUT('school-assign'))
    );

    // Pre-defined password => LASTNAME_DEPED
    $required_fields['password'] = strtoupper($required_fields['lastname']) . "_DEPED";

    // Add @deped.gov.ph suffix to usernames
    $required_fields['username'] = $required_fields['username'] . "@deped.gov.ph";

    // Check for empty fields 
    $hasEmptyValues = Utils::GetEmptyKeyInArray($required_fields);
    
    if ($hasEmptyValues) {
        $errCount++;
        $errMsg = "Submit failed! The required fields are incomplete or you may have entered an invalid value. Please try again.";
        return;
    }
    
    $db = Singleton::GetDbHelperInstance();

    // Check if username exists
    $checkUname = $db -> SelectRow_Where(Constants::$TEACHERS_TABLE, ["username" => $required_fields["username"]]);

    if (!empty($checkUname))
    {
        $errCount++;
        $errMsg = "Username is taken. Please choose another.";
        return;
    }
    
    $insert = $db -> InsertRow(Constants::$TEACHERS_TABLE, $required_fields);
    
    if (!$insert)
    {
        $errCount++;
        $errMsg = "Process Failed!. There was an error while processing the transaction.";
        return;
    }
    
    $successCount++;
    $successMsg = "Account has been created successfully!"; 
}
