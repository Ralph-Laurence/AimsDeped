<?php

require_once "includes/autoloader.inc.php";
require_once "includes/update-student-profile.inc.php";

session_start();

// if (!isset($_SESSION["IsLoggedIn"]) || (!$_SESSION["IsLoggedIn"])) {
//     Utils::RedirectTo("index.php"); 
//     exit;
// }

// Load the login cookie
$authCookie = Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
}
  
$cond = array("id" => $authCookie["userid"]);

$res = Singleton::GetDbHelperInstance()->SelectRow_Where(Constants::$STUDENTS_TABLE, $cond, true);

if (empty($res)) {
    // Failed to load student data
    Utils::RedirectTo("404.php");
    exit;
}

//
// Radio Button For Family Income
//
$totalFamIncome = $res["total_fam_income"];

function setIncomeChecked($compareWith)
{
    global $totalFamIncome;

    if ($totalFamIncome == $compareWith)
        echo "checked";
}
//
// Radio Button For Hand Type
//
$handType = $res["hand_type"];
$handTypes = array("Left", "Right", "Ambidextrous");

function setHandTypeChecked($compareWith)
{
    global $handType;
    global $handTypes;

    if ($handType == $compareWith || !in_array($handType, $handTypes))
        echo "checked";
}

function setHandTypeText()
{
    global $handType;
    global $handTypes;

    if (!(in_array($handType, $handTypes)))
        echo htmlspecialchars($handType);
}

//
// Radio Button For Speech Impairment
//
$speechCondition = $res["speech_condition"];
$conditionTypes = array("Normal", "With Impairment");

function setSpeechChecked($compareWith)
{
    global $speechCondition;
    global $conditionTypes;

    if ($speechCondition == $compareWith || !in_array($speechCondition, $conditionTypes))
        echo "checked";
}

function setSpeechImpairment()
{
    global $speechCondition;

    if ($speechCondition != "Normal")
        echo htmlspecialchars($speechCondition);
}

//
// Radio Button For Hearing Impairment
//
$hearingCondition = $res["hearing_condition"];

function setHearingChecked($compareWith)
{
    global $hearingCondition;
    global $conditionTypes;

    if ($hearingCondition == $compareWith || !in_array($hearingCondition, $conditionTypes))
        echo "checked";
}

function setHearingImpairment()
{
    global $hearingCondition;

    if ($hearingCondition != "Normal")
        echo htmlspecialchars($hearingCondition);
}

//
// Radio Button For Vision Type
//
$visionType = $res["vision_type"];

function setVisionChecked($compareWith)
{
    global $visionType;

    if ($visionType == $compareWith)
        echo "checked";
}

//
// Radio Button For Gender
//
$gender = $res["gender"];

function setGenderChecked($compareWith)
{
    global $gender;

    if ($gender == $compareWith)
        echo "checked";
}

// For processing dates
date_default_timezone_set("Asia/Manila");
$doc = Singleton::GetBoilerPlateInstance();
$doc->BeginHTML();
?>
<div class="wrapper w-100 opacity-overlay">

    <div id="main-content-wrapper" class="main-content-wrapper px-0 px-md-5 w-100">

        <!--NAV BAR-->
        <nav class="navbar navbar-expand-lg shadow-0">
            <div class="container-fluid">

                <div class="navbar-brand">
                    <img src="assets/img/deped-logo-m.png" id="main-logo" alt="logo" width="144" height="72">
                </div>

                <a href="logout.php" class="btn btn-dark d-inline-flex btn-primary-override btn-rounded justify-content-center align-items-center">
                    <span>Log Out</span>
                    <i class="material-icons-outlined navbar-button-icon">navigate_next</i>
                </a>
            </div>
        </nav>


        <!--CONTENT-->
        <div class="content-main p-4">

            <!--HEADING TITLE-->
            <div class="row mb-3">
                <p class="display-8 text-center text-sm-start">Update Student Profile</p>
            </div>

            <!--MAIN-->
            <div class="row border rounded bg-light p-4 align-items-center justify-content-center d-inline-flex">

                <div class="row mb-4">
                    <h5 class="text-primary">Personal Information</h5>
                </div> 
                <form class="row needs-validation" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" novalidate>
                <?php
                if ($errCount > 0) 
                {
                    echo "
                         <div class=\"row mt-2\">
                             <div class=\"col\">
                                 <div class=\"alert alert-danger text-center\" role=\"alert\">
                                 $errMsg
                                 </div>
                             </div>
                         </div>";
                }
                if ($successCount > 0) 
                {
                    echo "
                         <div class=\"row mt-2\">
                             <div class=\"col\">
                                 <div class=\"alert alert-success text-center\" role=\"alert\">
                                 $successMsg
                                 </div>
                             </div>
                         </div>";
                }
                ?>
                   <input type="hidden" name="input_userId" value="<?php echo $authCookie["userid"]; ?>" />
                    <!--INPUT GROUP 1-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_fname" name="input_fname" class="form-control" required value="<?php echo htmlspecialchars($res["firstname"]) ?>" />
                                <label class="form-label" for="input_fname">First name</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_mname" name="input_mname" class="form-control" required value="<?php echo htmlspecialchars($res["middlename"]) ?>" />
                                <label class="form-label" for="input_mname">Middle name</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_lname" name="input_lname" class="form-control" required value="<?php echo htmlspecialchars($res["lastname"]) ?>" />
                                <label class="form-label" for="input_lname">Last name</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="date" id="birthday_picker" name="birthday_picker" class="form-control bg-light" required value='<?php echo date('Y-m-d', strtotime($res["birthday"])) ?>' />
                                <label class="form-label" for="birthday_picker">Birthday</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 2-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_houseNo" name="input_houseNo" class="form-control" required value="<?php echo htmlspecialchars($res["present_house_no"]) ?>" />
                                <label class="form-label" for="input_houseNo">House #</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_brgy" name="input_brgy" class="form-control" required value="<?php echo htmlspecialchars($res["present_brgy"]) ?>" />
                                <label class="form-label" for="input_brgy">Brgy.</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_townCity" name="input_townCity" class="form-control" required value="<?php echo htmlspecialchars($res["present_town_city"]) ?>" />
                                <label class="form-label" for="input_townCity">Town/City</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_province" name="input_province" class="form-control" required value="<?php echo htmlspecialchars($res["present_province"]) ?>" />
                                <label class="form-label" for="input_province">Province</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 3-->
                    <div class="row mb-3">
                        <h6><span class="fw-bold">Permanent Residence</span> (to be updated by adviser whenever there is a change)</h6>
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_permHouseNo" name="input_permHouseNo" class="form-control" required value="<?php echo htmlspecialchars($res["perm_house_no"]) ?>" />
                                <label class="form-label" for="input_permHouseNo">House #</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_permBrgy" name="input_permBrgy" class="form-control" required value="<?php echo htmlspecialchars($res["perm_brgy"]) ?>" />
                                <label class="form-label" for="input_permBrgy">Brgy.</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_permTownCity" name="input_permTownCity" class="form-control" required value="<?php echo htmlspecialchars($res["perm_townCity"]) ?>" />
                                <label class="form-label" for="input_permTownCity">Town/City</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_permProvince" name="input_permProvince" class="form-control" required value="<?php echo htmlspecialchars($res["perm_province"]) ?>" />
                                <label class="form-label" for="input_permProvince">Province</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="checkbox_sameResidence" />
                                <label class="form-check-label" for="checkbox_sameResidence">Same as residence</label>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 4-->
                    <div class="row mb-3">
                        <!-- <h6><span class="fw-bold">Family</span></h6> -->
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_fathersName" name="input_fathersName" class="form-control" required value="<?php echo htmlspecialchars($res["father_name"]) ?>" />
                                <label class="form-label" for="form3Example1">Father's name</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                             
                            <div class="btn-group w-100 dropup custom-droplist">
                                <button class="btn w-100 text-wrap btn-secondary dropdown-toggle fw-bold text-capitalize" type="button" id="father_educ_droplist" data-mdb-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
                                    <?php echo htmlspecialchars($res["father_educ"] ?: "Educational Attainment") ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="father_educ_droplist">

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','Elementary Undergraduate'); 
                                            setElemText('father_educ_droplist','Elementary Undergraduate')">Elementary Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','Elementary Graduate'); 
                                            setElemText('father_educ_droplist','Elementary Graduate')">Elementary Graduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','High School Undergraduate');
                                            setElemText('father_educ_droplist','High School Undergraduate')">High School Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','High School Graduate');
                                            setElemText('father_educ_droplist','High School Graduate')">High School Graduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','College Undergraduate');
                                            setElemText('father_educ_droplist','College Undergraduate')">College Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_fathersEduc','College Graduate');
                                            setElemText('father_educ_droplist','College Graduate')">College Graduate
                                    </button>
                                </div>
                                <input type="hidden" id="input_fathersEduc" name="input_fathersEduc" value="<?php echo htmlspecialchars($res["father_educ"]) ?>">
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_fathersOccu" name="input_fathersOccu" class="form-control" required value="<?php echo htmlspecialchars($res["father_occu"]) ?>" />
                                <label class="form-label" for="input_fathersOccu">Occupation</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_fathersContact" name="input_fathersContact" class="form-control" required value="<?php echo htmlspecialchars($res["father_contact"]) ?>" />
                                <label class="form-label" for="input_fathersContact">Contact #</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 5-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-outline">
                                <input type="text" id="input_mothersName" name="input_mothersName" class="form-control" required value="<?php echo htmlspecialchars($res["mother_name"]) ?>" />
                                <label class="form-label" for="input_mothersName">Mother's name</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                             
                            <div class="btn-group w-100 dropup custom-droplist">
                                <button class="btn w-100 text-wrap btn-secondary dropdown-toggle fw-bold text-capitalize" type="button" id="mother_educ_droplist" data-mdb-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
                                    <?php
                                    echo htmlspecialchars($res["mother_educ"] ?: "Educational Attainment")
                                    ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="mother_educ_droplist">

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','Elementary Undergraduate'); 
                                            setElemText('mother_educ_droplist','Elementary Undergraduate')">Elementary Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','Elementary Graduate'); 
                                            setElemText('mother_educ_droplist','Elementary Graduate')">Elementary Graduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','High School Undergraduate');
                                            setElemText('mother_educ_droplist','High School Undergraduate')">High School Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','High School Graduate');
                                            setElemText('mother_educ_droplist','High School Graduate')">High School Graduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','College Undergraduate');
                                            setElemText('mother_educ_droplist','College Undergraduate')">College Undergraduate
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_mothersEduc','College Graduate');
                                            setElemText('mother_educ_droplist','College Graduate')">College Graduate
                                    </button>
                                </div>
                                <input type="hidden" id="input_mothersEduc" name="input_mothersEduc" value="<?php echo htmlspecialchars($res["mother_educ"]) ?>" />
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_mothersOccu" name="input_mothersOccu" class="form-control" required value="<?php echo htmlspecialchars($res["mother_occu"]) ?>" />
                                <label class="form-label" for="input_mothersOccu">Occupation</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_mothersContact" name="input_mothersContact" class="form-control" required value="<?php echo htmlspecialchars($res["mother_contact"]) ?>" />
                                <label class="form-label" for="input_mothersContact">Contact #</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 6-->
                    <div class="row mb-3">
                        <div class="col col-md-3 col-sm-6 ">
                            <span class="fw-bold">Birth Order</span>
                            <div class="btn-group w-100 dropup custom-droplist">
                                <button class="btn w-100 text-wrap btn-secondary dropdown-toggle fw-bold text-capitalize" type="button" id="birth_order_select_button" name="birth_order" data-mdb-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
                                    <?php 
                                        $order = $res["birth_order"];
                                        $orders = array("Eldest", "Youngest");
                                        if (!in_array($order, $orders))
                                            echo "Others";
                                        else
                                            echo $order;
                                    ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="birth_order_select_button">

                                    <button class="dropdown-item text-light" type="button" 
                                            onclick="setInputReadOnly('input_birth_order','Eldest'); 
                                                setInputVal('input_birth_order','Eldest');
                                                setElemText('birth_order_select_button','Eldest')">Eldest
                                    </button>

                                    <button class="dropdown-item text-light" type="button" 
                                            onclick="setInputReadOnly('input_birth_order','Youngest');
                                                setInputVal('input_birth_order','Youngest');
                                                setElemText('birth_order_select_button','Youngest')">Youngest
                                    </button>

                                    <button class="dropdown-item text-light" type="button" onclick="enableElementOnClick('input_birth_order');
                                                clearInput('input_birth_order');
                                                unsetInputReadOnly('input_birth_order'); 
                                                setElemText('birth_order_select_button', 'Other')">Other (Please specify)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col col-sm-6 col-md-3">
                            <div class="mt-4"></div>
                            <div class="form-outline">
                                <input type="text" id="input_birth_order" name="input_birth_order" class="form-control" required readonly value="<?php echo htmlspecialchars($res["birth_order"]) ?>" />
                                <label class="form-label" for="input_birth_order">Birth Order (Specify)</label>
                                <div class="invalid-feedback">Please select your birth order.</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col col-sm-6 col-md-3">
                            <div class="mt-3 d-md-none d-block"></div>
                            <span class="fw-bold">No. Siblings</span>
                            <div class="btn-group w-100 dropup custom-droplist">
                                <button class="btn w-100 text-wrap btn-secondary dropdown-toggle fw-bold text-capitalize" type="button" id="siblings_droplist" data-mdb-toggle="dropdown" aria-haspopup="true" aria-expanded="false" required>
                                    <?php echo htmlspecialchars($res["no_siblings"] ?: "No. of siblings") ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="siblings_droplist">
                                    <?php for ($i = 0; $i <= 15; $i++) { ?>
                                        <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_siblings', <?php echo $i ?>); 
                                            setElemText('siblings_droplist',<?php echo $i ?>)"> <?php echo $i ?>
                                        </button>
                                    <?php } ?>
                                </div>
                                <!-- <input type="hidden" name="input_birth_order"> -->
                            </div>
                             
                            <input type="hidden" id="input_siblings" name="input_siblings" required value="<?php echo htmlspecialchars($res["no_siblings"]) ?>" />
                        </div>
                    </div>
                    <!--INPUT GROUP 7-->
                    <div class="row mb-5">
                        <div class="col col-md-3 col-sm-6 ">
                            <span class="fw-bold">Living With</span>
                            <!-- <div class="mt-4"></div> -->
                            <div class="btn-group w-100 dropup custom-droplist">
                                <button class="btn w-100 text-wrap btn-secondary dropdown-toggle fw-bold text-capitalize" type="button" id="living_with_button" data-mdb-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo htmlspecialchars($res["living_with"]) ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="living_with_button">
                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_living_with','Parents');setElemText('living_with_button','Parents'); disableElementsOnClick(['input_guardianAddress','input_guardianRelation','input_guardianName'])">Parents</button>
                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_living_with','Father');setElemText('living_with_button','Father'); disableElementsOnClick(['input_guardianAddress','input_guardianRelation','input_guardianName'])">Father</button>
                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_living_with','Mother');setElemText('living_with_button','Mother'); disableElementsOnClick(['input_guardianAddress','input_guardianRelation','input_guardianName'])">Mother</button>
                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_living_with','Relative');setElemText('living_with_button','Relative'); disableElementsOnClick(['input_guardianAddress','input_guardianRelation','input_guardianName'])">Relative</button>
                                    <button class="dropdown-item text-light" type="button" onclick="setInputVal('input_living_with','Others');setElemText('living_with_button','Others'); enableElementsOnClick(['input_guardianAddress','input_guardianRelation','input_guardianName'])">
                                        Others (Please specify)
                                    </button>
                                    <input type="hidden" name="input_living_with" id="input_living_with" value="<?php echo htmlspecialchars($res["living_with"]) ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="col col-sm-6 col-md-3">
                            <div>(If living with relative/others)</div>
                            <div class="form-outline">
                                <input type="text" id="input_guardianName" name="input_guardianName" class="form-control" required readonly value="<?php echo htmlspecialchars($res["guardian_name"]) ?>" />
                                <label class="form-label" for="input_guardianName">Name of guardian</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col col-sm-6 col-md-3">
                            <div class="mt-4"></div>
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_guardianRelation" name="input_guardianRelation" class="form-control" required readonly value="<?php echo htmlspecialchars($res["guardian_relation"]) ?>" />
                                <label class="form-label" for="input_guardianRelation">Relationship</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                        <div class="col col-sm-6 col-md-3">
                            <div class="mt-4"></div>
                            <div class="mt-3 d-md-none d-block"></div>
                            <div class="form-outline">
                                <input type="text" id="input_guardianAddress" name="input_guardianAddress" class="form-control" required readonly value="<?php echo htmlspecialchars($res["guardian_address"]) ?>" />
                                <label class="form-label" for="input_guardianAddress">Guardian's Address</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <di class="col">
                            <h5>Please tick appropriate entry/ies</h5>
                        </di>
                    </div>
                    <!--INPUT GROUP 8-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Total Family Income</div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_below1k" value="1,000 and below" required <?php setIncomeChecked('1,000 and below'); ?> />
                                <label class="form-check-label" for="radio_income_below1k">1,000 and below</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_1k3k" value="1,001-3,000" <?php setIncomeChecked('1,001-3,000'); ?> />
                                <label class="form-check-label" for="radio_income_1k3k">1,001-3,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_3k5k" value="3,001-5,000" <?php setIncomeChecked('3,001-5,000'); ?> />
                                <label class="form-check-label" for="radio_income_3k5k">3,001-5,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_5k10k" value="5,001-10,000" <?php setIncomeChecked('5,001-10,000'); ?> />
                                <label class="form-check-label" for="radio_income_5k10k">5,001-10,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_10k20k" value="10,000-20,000" <?php setIncomeChecked('10,000-20,000'); ?> />
                                <label class="form-check-label" for="radio_income_10k20k">10,000-20,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_income" id="radio_income_20k" value="20,001 and above" <?php setIncomeChecked('20,001 and above'); ?> />
                                <label class="form-check-label" for="radio_income_20k">20,001 and above</label>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Hand used in writing</div>
                            <div class="form-check">
                                <input class="form-check-input radio-hand-used" type="radio" name="radio_hand" id="input_hand_left" value="Left" required <?php setHandTypeChecked('Left'); ?> onclick="clearInput('input_hand_used')" />
                                <label class="form-check-label" for="input_hand_left">Left</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radio-hand-used" type="radio" name="radio_hand" id="input_hand_right" value="Right" <?php setHandTypeChecked('Right'); ?> onclick="clearInput('input_hand_used')" />
                                <label class="form-check-label" for="input_hand_right">Right</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input radio-hand-used" type="radio" name="radio_hand" id="input_hand_ambi" value="Ambidextrous" <?php setHandTypeChecked('Ambidextrous'); ?> onclick="clearInput('input_hand_used')" />
                                <label class="form-check-label" for="input_hand_ambi">Ambidextrous</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="radio_hand" id="input_hand_other" value="Others" onclick="enableElementOnClick('input_hand_used')" <?php setHandTypeChecked('Others'); ?> />
                                <label class="form-check-label" for="input_hand_other">Others if not hand</label>
                            </div>
                            <div class="form-outline w-100 w-sm-25 w-md-50">
                                <input type="text" id="input_hand_used" name="input_hand_used" class="form-control" readonly required value="<?php setHandTypeText(); ?>" />
                                <label class="form-label" for="input_hand_used">Specify</label>
                                <div class="invalid-feedback">Please fill out this field.</div>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 9-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Visual Acuity / Vision</div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_vision" id="radio_vision_normal" value="Normal" required <?php setVisionChecked('Normal') ?> />
                                <label class="form-check-label" for="radio_vision_normal">Normal</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_vision" id="radio_vision_farsight" value="Far-Sighted" <?php setVisionChecked('Far-Sighted'); ?> />
                                <label class="form-check-label" for="radio_vision_farsight">Far-Sighted</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_vision" id="radio_vision_nearsight" value="Near-Sighted" <?php setVisionChecked('Near-Sighted'); ?> />
                                <label class="form-check-label" for="radio_vision_nearsight">Near-Sighted</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_vision" id="radio_vision_corrective" value="Wears Corrective Glasses" <?php setVisionChecked('Wears Corrective Glasses'); ?> />
                                <label class="form-check-label" for="radio_vision_corrective">Wears Corrective Glasses</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_vision" id="radio_vision_reading" value="Uses Reading Glasses" <?php setVisionChecked('Uses Reading Glasses'); ?> />
                                <label class="form-check-label" for="radio_vision_reading">Uses Reading Glasses</label>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Hearing Condition</div>
                            <div class="form-check">
                                <input class="form-check-input radio-normal-hearing" type="radio" name="radio_hearing" id="radio_hearing_normal" value="Normal" required <?php setHearingChecked('Normal'); ?> onclick="clearInput('input_hearing_impair')"/>
                                <label class="form-check-label" for="radio_hearing_normal">Normal</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input radio-hearing-impairment" type="radio" name="radio_hearing" id="radio_hearing_impair" value="With Impairment" <?php setHearingChecked('With Impairment'); ?> />
                                <label class="form-check-label" for="radio_hearing_impair">With Impairment</label>
                            </div>
                            <div class="form-outline w-100 w-sm-25 w-md-50">
                                <input type="text" id="input_hearing_impair" name="input_hearing_impair" class="form-control" readonly required value="<?php setHearingImpairment(); ?>" />
                                <label class="form-label" for="input_hearing_impair">Specify Impairment</label>
                            </div>
                        </div>
                    </div>
                    <!--INPUT GROUP 10-->
                    <div class="row mb-3">
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Speech Condition</div>
                            <div class="form-check">
                                <input class="form-check-input radio-normal-speech" type="radio" name="radio_speech" id="radio_speech_normal" value="Normal" required <?php setSpeechChecked('Normal'); ?> onclick="clearInput('input_speech_impair')"/>
                                <label class="form-check-label" for="radio_speech_normal">Normal</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input radio-speech-impairment" type="radio" name="radio_speech" id="radio_speech_withimpair" value="With Impairment" <?php setSpeechChecked('With Impairment'); ?> />
                                <label class="form-check-label" for="radio_speech_withimpair">With Impairment</label>
                            </div>
                            <div class="form-outline w-sm-25 ">
                                <input type="text" id="input_speech_impair" name="input_speech_impair" class="form-control" readonly required value="<?php setSpeechImpairment(); ?>" />
                                <label class="form-label" for="input_speech_impair">Specify Impairment</label>
                            </div>
                        </div>
                        <div class="w-100 d-block d-md-none"></div>
                        <div class="col">
                            <div class="mt-4"></div>
                            <div class="fw-bold mb-3">Gender</div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radio_gender" id="radio_boy" value="Boy" required <?php setGenderChecked('Boy'); ?> />
                                <label class="form-check-label" for="radio_boy">Boy</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="radio_gender" id="radio_girl" value="Girl" <?php setGenderChecked('Girl'); ?> />
                                <label class="form-check-label" for="radio_girl">Girl</label>
                            </div>
                            <!-- <div class="mt-4"></div>
                                <div class="fw-bold mb-3">Other observations</div>
                                <p>(like distinct appearance or behavior, learning difficulty, etc.), please state. </p>
                                <div class="form-outline">
                                    <textarea class="form-control" id="textAreaExample" rows="4"></textarea>
                                    <label class="form-label" for="textAreaExample">Observations</label>
                                </div> -->
                        </div>
                    </div>
                    <!--SUBMIT FORM-->
                    <div class="row mt-2">
                        <div class="col">
                            <div class="alert alert-warning text-center" role="alert">
                                Please double check all information before submitting.
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col d-flex justify-content-end">
                            <div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="checkbox_confirmAllInfo" />
                                    <label class="form-check-label" for="checkbox_confirmAllInfo">
                                        I certify that all information provided above are accurate and complete.
                                    </label>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" name="submit" disabled="disabled" id="submit-button" class="btn btn-primary btn-primary-override btn-rounded">Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!--CONTENT-->

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade w-100 h-100" id="mdb-modal" tabindex="-1" aria-labelledby="mdb-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center" id="mdb-modalLabel">
                        <span class="material-icons-outlined me-3">warning</span>
                        <span id="modal-title">Alert</span>
                    </h5>
                    <button type="button" class="btn-close me-2" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">...</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mdb-modal-close-btn" data-mdb-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="ClearMsgBox()" data-mdb-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="assets/js/student-profile.js"></script>
<script>
    var mdb_modal = undefined;

    $(document).ready(() => 
    {
        // Initialize Modal Box
        mdb_modal = new mdb.Modal(document.getElementById('mdb-modal'), []);

        // Enable readonly fields 
        CheckForLoadedSpecialFields();
    });

    function ShowMsgBox(title, msg, showCloseButton) {
        $("#modal-title").text(title);
        $(".modal-body").text(msg);

        if (showCloseButton) {
            $(".mdb-modal-close-btn").show();
        }

        mdb_modal.show();
    }

    function ClearMsgBox() {
        mdb_modal.close();
        $(".mdb-modal-close-btn").show();
        $("#modal-title").text('');
        $(".modal-body").text('');
    }
</script>


<?php $doc->EndHTML(); ?>