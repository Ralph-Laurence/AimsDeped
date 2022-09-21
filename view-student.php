<?php
date_default_timezone_set("Asia/Manila");
require_once "includes/autoloader.inc.php";

// Load the login cookie
$authCookie = AuthSession::Load();

// Restrict Teacher
if (isset($_SESSION['chmod'])) {
    if ($_SESSION['chmod'] < 777) {
        Utils::RedirectTo("403.php");
    }
}

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

$db = Singleton::GetDbHelperInstance();

$sql_teacher_info = "SELECT 
t.id,
CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'teacher_name',
s.school_id AS 'SchoolAssign',
s.school_name
FROM teachers t 
left join schools s ON t.school_assigned = s.school_id
WHERE t.id =?";

$sth_teacher_info = $db->Pdo->prepare($sql_teacher_info);
$sth_teacher_info->execute([$authCookie["userid"]]);
$teacher_info = $sth_teacher_info->fetch(PDO::FETCH_ASSOC) ?: [];

if (empty($teacher_info)) {
    // Failed to load teacher's data
    Utils::RedirectTo("400.php");
    exit;
}

//######################################
// REGION: EXAM INFORMATION
//######################################

$lrn_key = Utils::Reveal(Utils::INPUT("input_key"));
$referrer = "teacher-landing-page.php";

// CHeck if LRN is  empty
if (empty($lrn_key)) {
    // CHeck if lrn is NOT present in session array
    if (!array_key_exists('temp_lrn', $_SESSION)) {
        Utils::RedirectTo($referrer);
        exit;
    }

    // Check if LRN session var is empty
    if (empty($_SESSION['temp_lrn'])) {
        Utils::RedirectTo($referrer);
        exit;
    }
}

// We expect that the $lrn is not empty...
// so, we can set it in a session var now
if (empty($_SESSION['temp_lrn']))
    $_SESSION['temp_lrn'] = $lrn_key;

// echo $_SESSION['temp_lrn'];


// $exam_query = "SELECT 
// x.record_title AS 'title',
// x.record_date AS 'date',
// x.score AS 'score',
// x.remarks AS 'remarks' 
// FROM `exam_record_sheet` x 
// left JOIN teachers t on t.id = x.teacher_id 
// left join students s on s.student_lrn = x.student_lrn 
// where x.teacher_id = ? and x.student_lrn = ?";

$exam_query = "SELECT 

x.record_id AS 'id',
x.record_title AS 'title',
x.record_date AS 'date',
x.score AS 'score',
x.total_items AS 'items',
x.proficiency_rating AS 'rating',
x.remarks AS 'remarks', 
x.conducted_by,

s.student_lrn AS 'lrn',
CONCAT(s.firstname, ' ', s.middlename, ' ', s.lastname) AS 'student_name',
s.school_assigned AS 'school_id'  

FROM `exam_record_sheet` x 
left JOIN teachers t on t.id = x.teacher_id 
left join students s on s.student_lrn = x.student_lrn  

where x.teacher_id =? and x.student_lrn =?";

// CONCAT(t2.firstname, ' ', t2.middlename, ' ', t2.lastname) AS 'conducted_by'
// LEFT JOIN teachers t2 ON t2.id = x.conducted_by

$sth = $db->Pdo->prepare($exam_query);
$sth->execute([$authCookie["userid"], $_SESSION['temp_lrn']]);
$exam_result = $sth->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="lib/bs5/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="lib/material-design-icons/material-icons.css">
    <link rel="stylesheet" href="styles/style.css">

    <title>Exam Record Sheet</title>
</head>

<body>
    <div class="main-container d-flex h-100">

        <!-- BEGIN: NAVIGATION -->
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
                <li class="active d-flex align-items-center">
                    <a class="text-decoration-none w-100" href="#!">
                        <span class="px-4">
                            <i class="material-icons-sharp">groups</i>
                            <span>Students</span>
                        </span>
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
        <!--END: NAVIGATION-->

        <!-- MAIN CONTENT -->
        <!-- ==================================== -->
        <div class="content">

            <!--BEGIN: NAVIGATION BAR-->
            <nav class="d-flex justify-content-center px-5 bg-white" id="nav_top">
                <div class="top_container px-3 d-flex justify-content-between align-items-center">
                    <div class="d-inline-flex align-items-center">
                        <h5 class="d-inline-flex align-items-center">
                            <span><?= $teacher_info['teacher_name']; ?></span>
                            <i class="material-icons-sharp">arrow_right</i>
                        </h5>
                        <h5 class="text-muted fst-italic">Teacher</h5>
                    </div>
                    <a href="logout.php" class="px-2 text-decoration-none" id="logout_btn">
                        <i class="fa-solid fa-power-off"></i>
                        Logout
                    </a>
                </div>
            </nav>
            <!--END: NAVIGATION BAR-->

            <!--BEGIN: TAB PILLS-->
            <div class="pills-navigator px-5 py-3 mb-2">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active d-inline-flex align-items-center" id="pills-exams-tab" data-bs-toggle="pill" data-bs-target="#pills-exams" type="button" role="tab" aria-controls="pills-exams" aria-selected="true">
                            <i class="material-icons-outlined me-2">assessment</i>
                            <span>Exams</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-inline-flex align-items-center" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                            <i class="material-icons-outlined me-2">person_4</i>
                            <span>Profile</span>
                        </button>
                    </li>
                </ul>
            </div>
            <!--END: TAB PILLS-->

            <!--BEGIN: TAB PANES-->
            <div class="tab-content d-flex flex-column flex-grow-1" id="pills-tabContent">

                <!--BEGIN: EXAMS PANE-->
                <div class="workspace px-5 pb-2 d-flex flex-column flex-grow-1 tab-pane fade show active" id="pills-exams" role="tabpanel" aria-labelledby="pills-exams-tab">

                    <!-- BEGIN: SCHOOL NAME -->
                    <!-- <div class="row">
                        <div class="col-3 text-start">
                            <div class="title-header-wrap">
                                <h4>Exam Record Sheet</h4>
                            </div> 
                        </div>
                    </div> -->
                    <!-- END: SCHOOL NAME -->

                    <!--SCHOOLNAME-->
                    <div class="row mb-3">
                        <div class="col-md text-start d-flex align-items-center">
                            <span class="fs-5">Exam Record Sheet</span>
                        </div>
                        <div class="col-md text-start d-flex align-items-center">
                            <span class="fs-5"><?= $teacher_info['school_name']; ?></span>
                        </div>
                        <div class="col-md text-start d-flex align-items-center justify-content-end">
                            <span class="fs-6">Total Records:<?= count($exam_result); ?></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="fs-6 fw-bold"><?= $exam_result[0]['student_name'] ?></div>
                            <div class="fs-6"><?= $_SESSION['temp_lrn']; ?></div>
                        </div>
                    </div>
                    <!--SCHOOLNAME-->


                    <!-- BEGIN: CONTROL RIBBONS -->
                    <div class="row control-ribbons-wrap">

                        <!--BEGIN LEFT: FILTERING -->
                        <div class="col">

                            <!--BEGIN: DISPLAY ITEMS IN HORIZONTAL ROW -->
                            <div class="d-inline-flex align-items-center flex-row">

                                <!-- BEGIN: SORT DROPDOWN -->
                                <div class="dropdown me-2">
                                    <button class="btn btn-primary dropdown-toggle d-inline-flex align-items-center" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="material-icons-sharp me-2">sort_by_alpha</i>
                                        <span>Sort</span>
                                    </button>
                                    <ul class="dropdown-menu filter-select" aria-labelledby="dropdownMenuButton1" style="z-index: 10;">
                                        <li><button class="dropdown-item">Latest</button></li>
                                        <li><button class="dropdown-item">Oldest</button></li>
                                    </ul>
                                </div>
                                <!-- END: SORT DROPDOWN -->

                                <!--BEGIN: EXPORT BUTTON-->
                                <button class="btn d-inline-flex align-items-center me-2 btn-primary text-light">
                                    <i class="material-icons-sharp me-2">upload</i>
                                    <span>Export</span>
                                </button>
                                <!--END: EXPORT BUTTON-->

                                <!--BEGIN: TRASH BIN-->
                                <button class="btn d-inline-flex align-items-center me-2 text-light btn-danger" data-toggle="tooltip" data-placement="top" title="Your recently deleted records will show up here.">
                                    <i class="material-icons-sharp me-2">recycling</i>
                                    <span>Bin</span>
                                </button>
                                <!--END: TRASH BIN-->

                            </div>
                            <!--END: DISPLAY ITEMS IN HORIZONTAL ROW -->

                        </div>
                        <!--END LEFT: FILTERING -->

                        <!--BEGIN RIGHT: PAGINATION -->
                        <div class="col d-flex align-items-center justify-content-end">
                            <!--BEGIN: ADD BUTTON-->
                            <button class="btn d-inline-flex align-items-center me-2 btn-success" data-bs-toggle="modal" data-bs-target="#exam-record-modal">
                                <i class="material-icons-sharp me-2">add</i>
                                <span>Create</span>
                            </button>
                            <!--END: ADD BUTTON-->

                            <!--BEGIN: TRASH BIN-->
                            <button class="btn d-inline-flex align-items-center me-2 btn-warning text-dark" data-toggle="tooltip" data-placement="top" title="Load existing data from CSV.">
                                <i class="material-icons-sharp me-2">downloading</i>
                                <span>Import</span>
                            </button>
                            <!--END: TRASH BIN-->
                        </div>
                        <!--END RIGHT: PAGINATION -->

                    </div>
                    <!-- END: CONTROL RIBBONS -->

                    <!--BEGIN: TABLE-->
                    <div class="row mt-3 d-flex flex-column flex-grow-1">
                        <div class="col p-0 position-relative">

                            <!--BEGIN: SCROLLABLE CONTENT-->
                            <div class="scrollable px-3 pt-0 position-absolute start-0 top-0 w-100 h-100" style="overflow-y: auto;">
                                <table class="table table-striped table-hover table-sm">

                                    <thead class="bg-dark text-light top-0 position-sticky" style="z-index: 5;">
                                        <tr>
                                            <th class="d-none"></th>
                                            <th scope="col" class="py-2 align-middle px-5">Title</th>
                                            <th scope="col" class="py-2 align-middle px-5">Date</th>
                                            <th scope="col" class="py-2 align-middle px-5">Score</th>
                                            <!-- <th scope="col" class="py-2 align-middle px-5">Total Items</th> -->
                                            <th scope="col" class="py-2 align-middle px-5">Rating</th>
                                            <th scope="col" class="py-2 align-middle px-5">Conducted By</th>
                                            <th scope="col" class="py-2 align-middle px-5">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (!empty($exam_result)) : ?>
                                            <?php foreach ($exam_result as $e) : ?>
                                                <tr>
                                                    <td scope="row" class="d-none"><?= $e['id'] ?></td>
                                                    <td class="py-2 px-5 align-middle"><?= $e['title'] ?></td>
                                                    <td class="py-2 px-5 align-middle"><?= Utils::DateFmt($e['date'], "M-d-Y") ?></td>
                                                    <td class="py-2 px-5 align-middle"><?= $e['score'] . " / " . $e['items'] ?></td>
                                                    <!-- <td class="py-2 px-5 align-middle"><= $e['items'] ?></td> -->
                                                    <td class="py-2 px-5 align-middle">
                                                        <?php
                                                        switch ($e['rating']) {
                                                            case Constants::$PROFXY_ADVANCED:
                                                                echo "<span class=\"badge bg-success\">Advanced</span>";
                                                                break;
                                                            case Constants::$PROFXY_INTERMEDIATE:
                                                                echo "<span class=\"badge bg-primary\">Intermediate</span>";
                                                                break;
                                                            case Constants::$PROFXY_BEGINNER:
                                                                echo "<span class=\"badge bg-warning text-dark\">Beginner</span>";
                                                                break;
                                                            default:
                                                                echo "<span class=\"badge bg-secondary\">Unknown</span>";
                                                                break;
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="py-2 px-5 align-middle">
                                                        <?= $e['conducted_by'] ?: "" ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <button class="btn btn-primary me-2 p-1 d-inline-flex align-items-center" data-toggle="tooltip" data-placement="top" title="Information">
                                                                <i class="material-icons-outlined">info</i>
                                                            </button>
                                                            <button class="btn btn-warning mx-2 p-1 d-inline-flex align-items-center" data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <i class="material-icons-sharp">edit</i>
                                                            </button>
                                                            <button class="btn btn-danger ms-2 p-1 d-inline-flex align-items-center" data-toggle="tooltip" data-placement="top" title="Delete">
                                                                <i class="material-icons-sharp">delete</i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!--END: SCROLLABLE CONTENT-->

                        </div>
                    </div>
                    <!--END: TABLE-->

                </div>
                <!--END: EXAMS PANE-->

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">Prf</div>
            </div>
            <!--END: TAB PANES-->

        </div>

        <!-- BEGIN: Modal -->
        <div class="modal alertbox" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <div class="d-inline-flex align-items-center">
                                <i class="material-icons-sharp modal-icon me-2"></i>
                                <span class="modal-title-text">..</span>
                            </div>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="MsgBoxCancelClicked()" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="modal-msg">...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="MsgBoxCancelClicked()">Close</button>
                        <button type="button" class="btn btn-primary" onclick="MsgBoxOKClicked()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!--END: MODAL-->

        <!-- BEGIN: ADD EXAM WINDOW -->
        <div class="modal fade" id="exam-record-modal" tabindex="-1" aria-labelledby="exam-record-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title d-inline-flex align-items-center justify-content-start" id="modal-title">
                            <i class="material-icons-outlined me-2">assessment</i>
                            <span>Create Exam Record</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="action.add-record-exam.php" method="POST" class="needs-validation" novalidate>

                            <input type="hidden" name="teacher_key" value="<?= Utils::Obfuscate($authCookie["userid"]) ?>">
                            <input type="hidden" name="student_key" value="<?= Utils::Obfuscate($_SESSION['temp_lrn']) ?>">
                            <input type="hidden" name="school_key" value="<?= Utils::Obfuscate($exam_result[0]['school_id']) ?>">
                            <!--EXAM TITLE and DATE-->
                            <div class="row mb-2">
                                <div class="col px-4">
                                    <label for="input_exam_title">Exam Title</label>
                                    <select class="form-select" name="input_exam_title" id="input_exam_title" aria-label="Select Exam" required>
                                        <option disabled selected value="">Select Exam</option>
                                        <option value="Exam 1">Exam 1</option>
                                        <option value="Exam 2">Exam 2</option>
                                        <option value="Exam 3">Exam 3</option>
                                        <option value="Exam 4">Exam 4</option>
                                    </select>
                                </div>
                                <div class="col px-4">
                                    <label for="input_exam_date">Exam Date</label>
                                    <input id="input_exam_date" name="input_exam_date" class="form-control" type="date" value="<?= date('Y-m-d'); ?>" required />
                                </div>
                            </div>

                            <!--SCORING-->
                            <div class="row mb-2">
                                <div class="col px-4">
                                    <label for="input_score">Score</label>
                                    <input type="number" id="input_score" name="input_score" class="form-control" min="1" max="100" required />
                                </div>
                                <div class="col px-4">
                                    <label for="input_total_items">Total Items</label>
                                    <input type="number" id="input_total_items" name="input_total_items" class="form-control" min="1" max="100" required />
                                </div>
                            </div>

                            <!--SCORE RATING AND TEACHER INFO-->
                            <div class="row mb-2">
                                <div class="col px-4">
                                    <label for="input_conducted_by">Conducted By</label>
                                    <input type="text" class="form-control" id="input_conducted_by" name="input_conducted_by">
                                </div>
                                <div class="col px-4">
                                    <label for="input_rating">Rating</label>
                                    <select class="form-select" name="input_rating" id="input_rating" aria-label="Select Rating" required>
                                        <option disabled selected value="">Select Rating</option>
                                        <option value="<?= Constants::$PROFXY_ADVANCED ?>">Advanced</option>
                                        <option value="<?= Constants::$PROFXY_INTERMEDIATE ?>">Intermediate</option>
                                        <option value="<?= Constants::$PROFXY_BEGINNER ?>">Beginner</option>
                                    </select>
                                </div>
                            </div>

                            <!--REMARKS-->
                            <div class="row mb-2">
                                <div class="col px-4">
                                    <label for="input_remarks">Remarks</label>
                                    <textarea class="form-control" placeholder="Optional comments " id="input_remarks" name="input_remarks" style="min-height: 60px; height: 60px; max-height: 90px;"></textarea>
                                </div>
                            </div>

                            <!--WARNING-->
                            <div class="row">
                                <div class="col px-4">
                                    <div class="alert alert-warning text-center py-2">Please double check all information before submitting.</div>
                                </div>
                            </div>

                            <!--CONFIRMATION-->
                            <div class="row">
                                <div class="col-1 ps-4">
                                    <input class="form-check-input me-2" type="checkbox" id="exam-certify-check" value="">
                                </div>
                                <div class="col pe-4">
                                    <label for="">I certify that all information above are accurate and complete.</label>
                                </div>
                            </div>
                            <input type="submit" name="submit-exam-record" id="submit-exam-record" class="d-none">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-submit-record" disabled>Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!--END: ADD EXAM WINDOW-->

        <!-- script -->
        <script src="lib/popper.min.js"></script>
        <script src="lib/bs5/js/bootstrap.min.js"></script>
        <script src="lib/jquery/jquery-3.6.1.min.js"></script>
        <script src="assets/js/main.js"></script>
        <!-- action for active link -->
        <script>
            $(".sidebar ul li").on('click', function() {
                $(".sidebar ul li.active").removeClass('active')
                $(this).addClass('active')
            })

            $(".open").on('click', function() {
                $(this).hasClass('fa-bars') ? toggleOpen($(this)) : toggleClose($(this))
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
        </script>


        <script>
            $(document).ready(function() {
                $(".alertbox").modal({
                    backdrop: 'static'
                });

                $('[data-toggle="tooltip"]').tooltip();

                onlyNumericInput("input_score");
                onlyNumericInput("input_total_items");

                $("#exam-certify-check").click(function(e) {
                    var checked = this.checked;
                    $(".btn-submit-record").prop('disabled', !checked);
                });

                $(".btn-submit-record").click(() => $("#submit-exam-record").click());

                <?php
                if (isset($_SESSION["action_add_exam_record_msg"]) && !empty($_SESSION["action_add_exam_record_msg"])) {
                    $msg = $_SESSION["action_add_exam_record_msg"];

                    if ($msg == "fail") {
                        echo "ShowInfo('Failure', 'Something went wrong while processing the exam information. You may have entered an empty or invalid value.\n\nPlease try again.')";
                    }
                    echo "ShowInfo('Success', 'Exam information successfully recorded!')";

                    unset($_SESSION["action_add_exam_record_msg"]);
                }
                ?>
            });

            function ShowInfo(title, msg) {
                $(".modal-icon").text('info');
                $(".modal-title-text").text(title);
                $(".modal-msg").text(msg);

                $(".alertbox").modal('show');
            }

            function ShowConfirm(title, msg) {
                $(".modal-icon").text('help');
                $(".modal-title-text").text(title);
                $(".modal-msg").html(msg);

                $(".alertbox").modal('show');
            }

            function HideMsgBox() {
                $(".alertbox").modal('hide');
                $(".modal-title-text").text('');
                $(".modal-msg").empty();
                $(".modal-icon").text('');
            }

            function MsgBoxOKClicked() {

            }

            function MsgBoxCancelClicked() {}

            function GetCellValueOnClick() {
                $("td").click(function() {
                    var rowIndex = $(this).closest("tr").index();
                    var colIndex = $(this).index();
                    alert("row index: " + rowIndex + "\nCol Index: " + colIndex);
                })
            }
        </script>

        <script>
            //
            // INTERCEPT FORM SUBMISSION
            //
            (function() {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })()
        </script>
</body>

</html>