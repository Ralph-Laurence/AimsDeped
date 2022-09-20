<?php   
// Passed from url query 
// We will use this to determine if login mode is for
// SDO. Admin, Coord, Teacher or Students 
 
require_once("includes/autoloader.inc.php");
require_once("includes/login-auth.inc.php");

if (isset($_SESSION['login_request']) && $_SESSION['login_request'] > -1) 
{
    switch ($_SESSION['login_request']) {
        case 0:
            Utils::RedirectTo("sdoadmin.php");
            break;
        case 1:
            Utils::RedirectTo("coordinator.php");
            break;
        case 2:
            Utils::RedirectTo("teacher-landing-page.php");
            break;
        case 3:
            Utils::RedirectTo("student-profile.php");
            break;
        default:
            Utils::RedirectTo("login.php");
            break;
    }
}
   
// BEGIN DOCUMENT LAYPUT
$doc = Singleton::GetBoilerPlateInstance(); 
$doc -> BeginHTML(); 
?>

<!-- START OF BODY -->
<!--MAIN-->
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6 col-lg-4 col-sm-7 p-6 mb-5">

            <!-- LOGO -->
            <div class="text-center">
                <img class="mb-4" src="assets/img/deped-logo-m.png" style="width: 192px; height: 96px" />
            </div>

            <div class="border bg-light p-4 rounded-2">
                <!-- LOGIN ERROR -->
                <?php
                   $display = ($login_err > 0) ? "d-block" : "d-none";
                    echo "<div class=\"alert alert-danger text-center $display\" role=\"alert\">$login_err_msg</div>";
                ?>

                <div class="text-center">
                    <h6 class="mb-5 font-col-dark fw-bold">Login to your account</h6>
                </div>

                <!-- LOGIN BOX -->
                <form method="POST" action="">

                    <!--USERTYPE--> 
                    <input type="hidden" name="input-userType" value="<?php echo $userMode; ?>">
                
                    <!-- Username input -->
                    <div class="form-outline mb-4">
                        <input type="text" name="input-username" id="input-username" class="form-control" required />
                        <label class="form-label" for="input-username">Username</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input type="password" name="input-password" id="input-password" class="form-control" required />
                        <label class="form-label" for="input-password">Password</label>
                    </div>

                    <!-- 2 column grid layout for inline styling -->
                    <!-- <div class="row mb-4">
                        <div class="col d-flex justify-content-center"> 
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="form2Example31" />
                                <label class="form-check-label" for="form2Example31"> Remember me </label>
                            </div>
                        </div>

                        <div class="col"> 
                            <a href="#!">Forgot password?</a>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <!-- Submit button -->
                            <button type="submit" name="submit" class="btn btn-primary btn-block btn-primary-override">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $doc -> EndHTML();?>