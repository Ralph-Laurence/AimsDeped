<?php

require_once "includes/autoloader.inc.php";
require_once "includes/sdo-create-coord.php";

 
// Load the login cookie
$authCookie = AuthSession::Load(); // Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
}

// Make sure that only the SDO can access the system
if ($authCookie[Constants::$SESSION_AUTH_USER_LEVEL] != Constants::$USER_LVL_SDO)
{
    Utils::RedirectTo("403.php");
    exit;
} 

$db = Singleton::GetDbHelperInstance();
$schools = $db->SelectAll(Constants::$SCHOOLS_TABLE);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>SDO PROFILE MANAGEMENT</title>
    <!-- MDB icon -->
    <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="lib/mdb/css/mdb.min.css" />
</head>

<body>
    <!-- Start your project here-->
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col d-flex flex-row-reverse">
                <a href="logout.php" class="btn btn-danger">Logout</a>
                <button class="btn btn-primary me-3">Import from CSV</button> 
            </div>
        </div>
        <div class="card">
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" autocomplete="off" class="needs-validation" novalidate>

                <?php
                if ($errCount > 0) {
                    echo "
                         <div class=\"row mt-2\">
                             <div class=\"col px-5\">
                                 <div class=\"alert alert-danger text-center\" role=\"alert\">
                                 $errMsg
                                 </div>
                             </div>
                         </div>";
                }
                if ($successCount > 0) {
                    echo "
                         <div class=\"row mt-2\">
                             <div class=\"col px-5\">
                                 <div class=\"alert alert-success text-center\" role=\"alert\">
                                 $successMsg
                                 </div>
                             </div>
                         </div>";
                }
                ?>

                <!-- Card header -->
                <div class="card-header py-4 px-5 bg-light border-0">
                    <h4 class="mb-0 fw-bold">Create School Coordinator</h4>
                </div>

                <!-- Card body -->
                <div class="card-body px-5">
                    <!-- Account section -->
                    <div class="row gx-xl-5">
                        <div class="col-md-4">
                            <h5>Personal Information</h5>
                        </div>

                        <div class="col-md-8">

                            <div class="row">
                                <div class="col">
                                    <label for="fname" class="form-label mt-3">First name</label>
                                    <div class="form-outline mb-3">
                                        <input type="text" class="form-control" id="fname" name="fname" style="max-width: 500px;" required />
                                        <div class="invalid-feedback">Please enter a valid Firstname.</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="mname" class="form-label mt-3">Middle name</label>
                                    <div class="form-outline mb-3">
                                        <input type="text" class="form-control" id="mname" name="mname" style="max-width: 500px;" required />
                                        <div class="invalid-feedback">Please enter a valid Middlename</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label for="lname" class="form-label mt-3">Last name</label>
                                    <div class="form-outline mb-3">
                                        <input type="text" class="form-control" id="lname" name="lname" style="max-width: 500px;" required />
                                        <div class="invalid-feedback">Please enter a valid Lastname</div>
                                    </div>

                                </div>
                                <div class="col">
                                    <label for="emailAddress" class="form-label mt-3">Email address</label>
                                    <div class=form-outline "mb-3">
                                        <input type="email" class="form-control" id="emailAddress" name="emailAddress" style="max-width: 500px;" required />
                                        <div class="invalid-feedback">Please enter a valid Email</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label for="contact" class="form-label mt-3">Phone number</label>
                                    <div class="form-outline mb-3">
                                        <input type="tel" maxlength="11" class="form-control" id="contact" name="contact" style="max-width: 300px;" required />
                                        <div class="invalid-feedback">Please enter a valid Phone Number</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5" />

                    <!-- Billing section -->
                    <div class="row gx-xl-5">
                        <div class="col-md-4">
                            <h5>Account</h5>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="username" class="form-label mt-3">Username</label>
                                    <div class="form-outline mb-3">
                                        <input type="text" class="form-control" id="username" name="username" required aria-describedby="username-helper" />
                                        <div class="invalid-feedback">Please enter a valid Username</div>
                                    </div>
                                    <div class="form-text" id="username-helper">Usernames are automatically suffixed with <span class="text-primary">@deped.gov.ph</span></div>
                                </div>

                                <div class="col-md-5">
                                    <div class="mb-3 mt-3">
                                        <label for="exampleInput6" class="form-label">School Assigned</label>
                                        <select class="form-select" name="school-assign" id="school-assign" aria-label="Default select example" required>
                                            <option disabled selected value="">Select School</option>

                                            <?php if (!empty($schools)) : ?>
                                                <?php foreach ($schools as $school) : ?>
                                                    <option value="<?= $school['school_id']; ?>"><?= $school['school_name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <hr class="my-5" />

                    <!-- Business address section -->
                    <!-- Password section -->
                    <!-- <div class="row gx-xl-5">
                        <div class="col-md-4">
                            <h5>Change password</h5>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInput11" class="form-label">Old password</label>
                                        <input type="password" class="form-control" id="exampleInput11" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleInput12" class="form-label">New password</label>
                                        <input type="password" class="form-control" id="exampleInput12" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row gx-xl-5">
                        <div class="col-md-4">
                            <h5>Password</h5>
                        </div>

                        <dov class="col-md-8">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <p>The default password for all user is <span class="fw-bold text-primary"><i>LASTNAME_DEPED</i></span></p>
                                        <small>Passwords can be changed later on.</small>
                                    </div>
                                </div>
                            </div>
                        </dov>
                    </div>
                    <div class="row">
                        <div class="col">
                            <hr class="hr">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="alert-warning p-3 text-center mt-2" role="alert">
                                Please double check all information before submitting.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="d-flex mt-3 flex-row-reverse">
                                <!-- Confirmation checkbox -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="certify_Check"/>
                                    <label class="form-check-label" for="flexCheckChecked">I certify that all information above are complete and accurate</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card footer -->
                <div class="card-footer text-end py-4 px-5 bg-light border-0">

                    <!-- <button class="btn btn-link btn-rounded" data-ripple-color="primary">Cancel</button> -->
                    <button type="submit" name="submit" class="btn btn-primary btn-rounded btn-submit-record" disabled>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- End your project here-->

    <!-- MDB -->
    <script type="text/javascript" src="lib/mdb/js/mdb.min.js"></script>
    <script src="lib/jquery/jquery-3.6.1.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript">
        //
        // Eable submit button on certify
        //
        $("#certify_Check").click(function(e)
        {
            var checked = this.checked;
            $(".btn-submit-record").prop('disabled', !checked);
        })
        //
        // INTERCEPT FORM SUBMISSION
        // Then apply custom form validity report
        //
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms).forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    // ShowMsgBox("Submit Failed", "There are incomplete required fields. Please complete them.", false);
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>

</html>