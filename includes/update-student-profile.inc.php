<?php
require_once("autoloader.inc.php"); 
 
$successCount = 0;
$successMsg = "";

$errCount = 0;
$errMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"]))
{ 
    //
    // Collect all iunput values
    //
    $required_inputs = array
    (
        "firstname" => Utils::INPUT("input_fname"),
        "middlename" => Utils::INPUT("input_mname"),
        "lastname" => Utils::INPUT("input_lname"),
        "birthday" => Utils::INPUT("birthday_picker"),
        "present_house_no" => Utils::INPUT("input_houseNo"),
        "present_brgy" => Utils::INPUT("input_brgy"),
        "present_town_city" => Utils::INPUT("input_townCity"),
        "present_province" => Utils::INPUT("input_province"),
        "perm_house_no" => Utils::INPUT("input_permHouseNo"),
        "perm_brgy" => Utils::INPUT("input_permBrgy"),
        "perm_townCity" => Utils::INPUT("input_permTownCity"),
        "perm_province" => Utils::INPUT("input_permProvince"),
        "father_name" => Utils::INPUT("input_fathersName"),
        "father_educ" => Utils::INPUT("input_fathersEduc"),
        "father_occu" => Utils::INPUT("input_fathersOccu"),
        "father_contact" => Utils::INPUT("input_fathersContact"),
        "mother_name" => Utils::INPUT("input_mothersName"),
        "mother_educ" => Utils::INPUT("input_mothersEduc"),
        "mother_occu" => Utils::INPUT("input_mothersOccu"),
        "mother_contact" => Utils::INPUT("input_mothersContact"),
        "no_siblings" => Utils::INPUT("input_siblings"),
        "vision_type" => Utils::INPUT("radio_vision"),
        "total_fam_income" => Utils::INPUT("radio_income"),
        "gender" => Utils::INPUT("radio_gender"),

        // These inputs require specific inputs
        "hearing_condition" => Utils::INPUT("radio_hearing"),
        "speech_condition" => Utils::INPUT("radio_speech"),
        "birth_order" => Utils::INPUT("input_birth_order"),
        "hand_type" => Utils::INPUT("radio_hand"),
        "living_with" => Utils::INPUT("input_living_with")
    );
    
    $specific_inputs =
    [
        "speech_impair" => Utils::INPUT("input_speech_impair"),
        "hearing_impair" => Utils::INPUT("input_hearing_impair"),
        "hand_used" => Utils::INPUT("input_hand_used"),
        "guardian_name" => Utils::INPUT("input_guardianName"),
        "guardian_relation" => Utils::INPUT("input_guardianRelation"),
        "guardian_address" => Utils::INPUT("input_guardianAddress")
    ];

    $userId = Utils::INPUT("input_userId");

    if (empty($userId))
    {
        $errCount++;
        $errMsg = "Fatal Error! Your user id was not loaded. Please contact the administrators.";
        return;
    }
    // No HANDS?
    if ($required_inputs["hand_type"] == "Others")
    {
        $required_inputs["hand_type"] = $specific_inputs["hand_used"];
    } 

    // Check for specific inputs, then replace their last value in required_inputs array
    // Hearing not NORMAL?: 
    if ($required_inputs["hearing_condition"] != "Normal")
    {
        $required_inputs["hearing_condition"] = $specific_inputs["hearing_impair"];
    }
    // Speech not NORMAL?
    if ($required_inputs["speech_condition"] != "Normal")
    {
        $required_inputs["speech_condition"] = $specific_inputs["speech_impair"];
    } 

    // Has GUARDIAN?
    if ($required_inputs["living_with"] == "Others")
    {
        $required_inputs["guardian_name"] = $specific_inputs["guardian_name"];
        $required_inputs["guardian_relation"] = $specific_inputs["guardian_relation"];
        $required_inputs["guardian_address"] = $specific_inputs["guardian_address"];
    }
    //
    // Do not allow empty fields.
    //
    $hasEmptyValues = Utils::GetEmptyKeyInArray($required_inputs);
    
    if ($hasEmptyValues)
    {
        $errCount++;
        $errMsg = "Submit failed! The required fields are incomplete or you may have entered an invalid value. Please try again.";
        return;
    }

    //
    // Force Empty on these fields
    // 
    if ($required_inputs["living_with"] != "Others")
    {
        $required_inputs["guardian_name"] = "";
        $required_inputs["guardian_relation"] = "";
        $required_inputs["guardian_address"] = "";
    }
 
    
    $db = Singleton::GetDbHelperInstance();

    // Condition
    $cond = ["id" => $userId];

    $update = $db -> UpdateWhereEquals(Constants::$STUDENTS_TABLE, $required_inputs, $cond);
    
    if (!$update)
    {
        $errCount++;
        $errMsg = "Process Failed!. There was an error while processing your information. Please contact the administrators.";
        return;
    }

    $successCount++;
    $successMsg = "Profile successfuly updated.";
}

?>