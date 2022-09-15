<?php
require_once "includes/autoloader.inc.php";

//######################################
// REGION: TEACHER's INFORMATION
//######################################
// Load the login cookie
$authCookie = Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
}
//
// Load the user's (teacher) information
//
$this_users_id = array("id" => $authCookie["userid"]);

$res = Singleton::GetDbHelperInstance()->SelectRow_Where(Constants::$TEACHERS_TABLE, $this_users_id, true);

if (empty($res)) {
    // Failed to load teacher's data
    Utils::RedirectTo("404.php");
    exit;
}
//
// We expect that all relevant info has been loaded
//
$firstName = $res['firstname'];

$db = Singleton::GetDbHelperInstance();
//
// Retrieve all classrooms that belong to this teacher
$sql_getclxrooms = "SELECT c.subject_name AS 'subjectName', g.level AS 'section' FROM `classrooms` c LEFT JOIN `teachers` t ON c.teacher_id = t.id LEFT JOIN `grade_section` g ON c.grade_section_id = g.id WHERE t.id = '3'";
$result = $db -> Pdo -> prepare($sql);
//
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <!-- Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles/style.css">

    <link rel="stylesheet" href="lib/material-design-icons/material-icons.css">
    <link rel="stylesheet" href="lib/mdb/css/mdb.min.css">
    <title>My Classrooms</title>
</head>

<body>
    <div class="main-container d-flex h-100">
        <!-- NAVIGATION -->
        <!-- ==================================== -->
        <div class="sidebar px-4 show" id="side_nav">

            <div class="header-box mx-auto d-flex flex-column justify-content-center align-items-center mt-4">
                <img src="assets/img/logo-white.png" alt="Logo">
            </div>

            <!-- TOGGLE BUTTON -->
            <i class="toggle-button open fa-solid fa-burger d-flex justify-content-center align-items-center"></i>

            <!-- LINKS -->
            <ul class="list-unstyled px-2 pt-5 pb-2">
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="material-icons-sharp">insights</i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="fa-solid fa-user"></i>
                        <span>Profile Management</span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="teacher.php">
                        <i class="material-icons-sharp">groups</i>
                        <span>Students</span>
                    </a>
                </li>
                <li class="active d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="material-icons-outlined">analytics</i>
                        <span>Classroom</span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="material-icons-sharp">badge</i>
                        <span>Marks</span>
                    </a>
                </li>
            </ul>

            <!-- DIVIDER / SEPARATOR LINE -->
            <hr class="h-color mx-2">

            <!-- LINKS -->
            <ul class="list-unstyled px-2 pt-1 pb-2">
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="fa-regular fa-gear"></i>
                        <span>Setting</span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="fa-regular fa-phone"></i>
                        <span>Contact Us</span>
                    </a>
                </li>
            </ul>

        </div>
        <!-- MAIN CONTENT -->
        <!-- ==================================== -->
        <div class="content">
            <nav class="px-4 py-1 d-flex justify-content-center px-5 bg-white" id="nav_top">
                <div class="top_container px-3 d-flex justify-content-between align-items-center">
                    <span>My Classrooms : 0</span>
                    <a href="logout.php" class="px-2 text-decoration-none" id="logout_btn">
                        <i class="fa-solid fa-power-off"></i>
                        Logout
                    </a>
                </div>
            </nav>
            <!-- CLASROOM CARDS WRAPPER -->
            <div class="classroom-cards">
                <div class="row px-4">
                    <div class="col-6 col-lg-4 col-md-6 col-sm-6 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6 col-sm-6 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 w-100 d-none d-lg-none d-md-block d-sm-block"></div>
                    <div class="col-6 col-lg-4 col-md-6 col-sm-4 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6 col-sm-6 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 w-100 d-none d-lg-none d-md-block d-sm-block"></div>
                    <div class="col-6 col-lg-4 col-md-6 col-sm-4 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 col-md-6 col-sm-6 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- CONFIRM ALL CHECKED STUDENTS -->
    <div class="overlay d-flex justify-content-center align-items-center hide" id="overlay">

        <!-- NOTICE TO SAVE PROMPT -->
        <div class="bg-white p-5 d-flex flex-column justify-content-center align-items-center position-relative" id="confirm_container">

            <div class="mt-3 text-center">
                <div class="primary_message">
                    <h2>Save new students?</h2>
                    <p>Once confirm you cannot revert this changes.</p>
                </div>
            </div>

            <div class="d-flex">
                <button class="confirm_cta close_btn">Cancel</button>
                <button class="confirm_cta text-white" id="save_all">Yes, save</button>
            </div>

            <!-- CLOSE MODAL -->
            <i class="fa-solid fa-xmark position-absolute close_btn" id="close_confirm"></i>
        </div>

    </div>

    <!-- script -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script> -->
    <script src="lib/jquery/jquery-3.6.1.min.js"></script>
    <!-- action for active link -->
    <script>
        $(".sidebar ul li").on('click', function() {
            $(".sidebar ul li.active").removeClass('active')
            $(this).addClass('active')
        })

        $(".open").on('click', function() {
            $(this).hasClass('fa-burger') ? toggleOpen($(this)) : toggleClose($(this))
        })

        $("#save_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        $(".close_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        function toggleOpen(el) {
            el.removeClass('fa-burger')
            el.addClass('fa-xmark')
            $("#side_nav").removeClass('show')

        }

        function toggleClose(el) {
            el.removeClass('fa-xmark')
            el.addClass('fa-burger')
            $("#side_nav").addClass('show')
        }
    </script>

</body>

</html>