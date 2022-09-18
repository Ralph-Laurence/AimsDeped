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

$db = Singleton::GetDbHelperInstance();
$res = $db->SelectRow_Where(Constants::$TEACHERS_TABLE, $this_users_id, true);

if (empty($res)) {
    // Failed to load teacher's data
    Utils::RedirectTo("404.php");
    exit;
}
//
// We expect that all relevant info has been loaded
//
$firstName = $res['firstname'];

//
// DUMMY DATA
//
$exam_subject = "Math";
$teacher_name = $res['firstname'] . " " . $res['middlename'] . " " . $res['lastname'];
$section = "5-A";

require_once("includes/get-exam-records.inc.php");
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
    <title>Teacher's Page</title>
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
            <i class="toggle-button open fa-solid fa-bars d-flex justify-content-center align-items-center"></i>

            <!-- LINKS -->
            <ul class="list-unstyled px-2 pt-5 pb-2">
                <li class="d-flex align-items-center">
                    <a class="text-decoration-none w-100" href="">
                        <span class="px-4">
                            <i class="material-icons-sharp">insights</i>
                            <span>Dashboard</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <a class="text-decoration-none w-100" href="">
                        <span class="px-4">
                            <i class="fa-solid fa-user"></i>
                            <span>Profile Management</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex align-items-center">
                    <a class="text-decoration-none w-100" href="teacher-landing-page.php">
                        <span class="px-4">
                            <i class="material-icons-sharp">groups</i>
                            <span>Students</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none w-100" href="section-handles.php">
                        <i class="material-icons-outlined">meeting_room</i>
                        <span>My Sections</span>
                    </a>
                </li>
                <li class="active d-flex align-items-center px-4">
                    <a class="text-decoration-none w-100" href="#!">
                        <i class="material-icons-sharp">badge</i>
                        <span>Exams</span>
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

            <!--UPPER NAVBAR-->
            <nav class="px-4 py-1 d-flex justify-content-center px-5 bg-white" id="nav_top">
                <div class="top_container px-3 d-flex justify-content-between align-items-center">
                    <h5>Exam Record Sheet</h5>
                    <a href="logout.php" class="px-2 text-decoration-none" id="logout_btn">
                        <i class="fa-solid fa-power-off"></i>
                        Logout
                    </a>
                </div>
            </nav>

            <!-- BEGIN: WORKSPACE -->

            <div class="container h-100 shadow">
                <!--BEGIN: SUBJECT TITLE-->
                <div class="row">
                    <div class="col text-center p-3">
                        <h2><?= $exam_subject; ?></h2>
                    </div>
                </div>
                <!--END: SUBJECT TITLE-->

                <!--BEGIN: SHEET FILTER FORM-->
                <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                    <input type="hidden" name="input_gradingPeriod" id="input_gradingPeriod" value="Select">
                    <input type="hidden" name="input_examSubject" id="input_examSubject" value="<?= $exam_subject; ?>">
                    <button type="submit-form-filter" class="invisible submit-form-filter"></button>
                </form> 
                <!--END: SHEET FILTER FORM-->

                <!--BEGIN: SHEET HEADERS -->
                <div class="sheet-headers px-5">
                    <div class="row">
                        <div class="col text-start">
                            <div class="d-block">
                                <div class="d-inline-flex flex-row align-items-center">
                                    <h5 class="me-2 text-muted">Teacher:</h5>
                                    <h5><?= $teacher_name; ?></h5>
                                </div>
                            </div>
                            <div class="d-block">
                                <div class="d-inline-flex flex-row align-items-center">
                                    <h5 class="me-2 text-muted">Grade & Section:</h5>
                                    <h5><?= $section; ?></h5>
                                </div>
                            </div>
                            <div class="d-block mt-2">
                                <div class="row">
                                    <div class="col-2 d-flex text-muted align-items-center fs-5">
                                        Grading Period:
                                    </div>
                                    <div class="col">
                                        <!-- Default dropright button -->
                                        <div class="btn-group dropend d-inline">
                                            <button type="button" class="btn btn-primary dropdown-toggle fw-bold text-capitalize fs-6 p-2" data-mdb-toggle="dropdown" aria-expanded="false">
                                                <?php echo $gradingPeriod ?: "Select"; ?>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <!-- <li><button type="button" onclick="GetExams('1m');" class="dropdown-item">1st Mid Quarter</button></li> -->
                                                <li><button type="button" onclick="GetExams('1q');" class="dropdown-item">Exam 1</button></li>
                                                <li>
                                                    <hr class="dropdown-divider" />
                                                </li>
                                                <!-- <li><button type="button" onclick="GetExams('2m');" class="dropdown-item">2nd Mid Quarter</button></li> -->
                                                <li><button type="button" onclick="GetExams('2q');" class="dropdown-item">Exam 2</button></li>
                                                <li>
                                                    <hr class="dropwdown-divider">
                                                </li>
                                                <!-- <li><button type="button" onclick="GetExams('3m');" class="dropdown-item">3rd Mid Quarter</button></li> -->
                                                <li><button type="button" onclick="GetExams('3q');" class="dropdown-item">Exam 3</button></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <!-- <li><button type="button" onclick="GetExams('4m');" class="dropdown-item">4th Mid Quarter</button></li> -->
                                                <li><button type="button" onclick="GetExams('finals');" class="dropdown-item">Finals</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END: SHEET HEADERS -->

                <!-- BEGIN: SHEET AREA -->
                <div class="sheet-area p-5">
                    <div class="row">
                        <div class="col">
                            <table class="table table-sm table-striped table-hover exams-table">
                                <thead>
                                    <tr>
                                        <th class="fw-bold" scope="col">LRN</th>
                                        <th class="fw-bold" scope="col">Name</th>
                                        <th class="fw-bold" scope="col">Score</th>
                                        <th class="fw-bold" scope="col">Proficiency</th>
                                        <th class="fw-bold" scope="col">Date</th>
                                        <!-- <th scope="col">Retake</th> -->
                                        <th scope="col">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="exams-table-body">
                                    <tr style="background-color: #E5DFB7;">
                                        <th scope="row">
                                            <small class="fw-bold">Average Score</small>
                                        </th>
                                        <td colspan="1"></td>
                                        <td>
                                            <div class="form-outline">
                                                <input type="text" id="form12" class="form-control bg-light" value="<?php if (!empty($exam_table_set)) echo $exam_table_set[0]["highest_possible"]; ?>" />
                                                <label class="form-label" for="form12">Highest Possible</label>
                                            </div>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-dark">
                                        <td colspan="6" class="py-1 px-4 fw-bold text-light" scope="row">Boys</td>
                                    </tr>
                                    <?php foreach ($exam_table_set as $set) : ?>
                                        <?php if ($set["gender"] == "Boy") : ?>
                                            <tr>
                                                <th scope="row"><?= $set['student_lrn']; ?></th>
                                                <td><?= $set['firstname'] . ' ' . $set['middlename'] . ' ' . $set['lastname']; ?></td>
                                                <td><?= $set["exam_score"] ?></td>
                                                <td><?= $set["proficiency"]; ?></td>
                                                <td><?= Utils::DateFmt($set['exam_date'], "F-d-Y"); ?></td>
                                                <!-- <td><= $set["retake"]; ?></td> -->
                                                <td><?= $set["remarks"]; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <tr class="bg-dark">
                                        <td colspan="6" class="py-1 px-4 fw-bold text-light" scope="row">Girls</td>
                                    </tr>
                                    <?php foreach ($exam_table_set as $set) : ?>
                                        <?php if ($set["gender"] == "Girl") : ?>
                                            <tr>
                                                <th scope="row"><?= $set['student_lrn']; ?></th>
                                                <td><?= $set['firstname'] . ' ' . $set['middlename'] . ' ' . $set['lastname']; ?></td>
                                                <td><?= $set["exam_score"] ?></td>
                                                <td><?= $set["proficiency"]; ?></td>
                                                <td><?= Utils::DateFmt($set['exam_date'], "F-d-Y"); ?></td>
                                                <!-- <td><= $set["retake"]; ?></td> -->
                                                <td><?= $set["remarks"]; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END: SHEET AREA -->
            </div>

            <!-- END: WORKSPACE -->
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
    <script src="lib/mdb/js/mdb.min.js"></script>
    <script src="assets/js/exam-record.js"></script>
    <!-- action for active link -->
    <script>
        $(".sidebar ul li").on('click', function() {
            $(".sidebar ul li.active").removeClass('active')
            $(this).addClass('active')
        })

        $(".open").on('click', function() {
            $(this).hasClass('fa-bars') ? toggleOpen($(this)) : toggleClose($(this))
        })

        $("#save_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        $(".close_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        function toggleOpen(el) {
            el.removeClass('fa-bars')
            el.addClass('fa-xmark')
            $("#side_nav").removeClass('show')

        }

        function toggleClose(el) {
            el.removeClass('fa-xmark')
            el.addClass('fa-bars')
            $("#side_nav").addClass('show')
        } 

        function GetExams(quarter)
        {
            $("#input_gradingPeriod").val(quarter);
            $(".submit-form-filter").click();
        }
    </script>

</body>

</html>