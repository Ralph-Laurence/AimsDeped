<?php
require_once "includes/autoloader.inc.php";
include_once 'includes/http-referer.inc.php';

session_start();

// Restrict Teacher
if (isset($_SESSION['chmod'])) {
    if ($_SESSION['chmod'] < 777) {
        Utils::RedirectTo("403.php");
    }
}

// Load the login cookie
$authCookie = AuthSession::Load(); // Auth::LoadAuthCookie();

// If there is no cookie, force login
if (empty($authCookie)) {
    Utils::RedirectTo("login.php");
    exit;
}

// Make sure that only the teacher can access the system
if ($authCookie[Constants::$SESSION_AUTH_USER_LEVEL] != Constants::$USER_LVL_TEACHER) {
    Utils::RedirectTo("403.php");
    exit;
}

//
// Load the user's (teacher) information
//
$this_users_id = array("id" => $authCookie["userid"]);

$db = Singleton::GetDbHelperInstance();

$teachers_table = Constants::$TEACHERS_TABLE;
$schools_table = Constants::$SCHOOLS_TABLE;

$teacher_query = "SELECT 
t.id,
t.username,
t.firstname,
t.middlename,
t.lastname,
t.school_assigned AS 'school_assigned',
s.school_name AS 'school_name'
FROM $teachers_table t
LEFT JOIN $schools_table s ON s.school_id = t.school_assigned
WHERE id =?";

$teacher_sql = $db->Pdo->prepare($teacher_query);
$teacher_sql->execute([$authCookie["userid"]]);
$teacher_info = $teacher_sql->fetch(PDO::FETCH_ASSOC) ?: [];

if (empty($teacher_info)) {
    // Failed to load teacher's data
    Utils::RedirectTo("404.php");
    exit;
}
//
// We expect that all relevant info has been loaded
//
$teacher_name = $teacher_info['firstname'] . " " . $teacher_info['middlename'] . " " . $teacher_info['lastname'];
//
// Masterlist of teachers
// 
$get_teachers_sql = "SELECT 
id, 
CONCAT(lastname, ', ', firstname, ' ', middlename) AS 'teacher',
s.school_id,
s.school_name AS 'school'
FROM $teachers_table t
LEFT JOIN $schools_table s ON s.school_id = t.school_assigned";

$get_teachers = $db->Pdo->prepare($get_teachers_sql); //("SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) AS 'teacher' FROM `teachers`");
$get_teachers->execute();
$teachers_result = $get_teachers->fetchAll(PDO::FETCH_ASSOC) ?: [];

//######################################
// REGION: STUDENT's INFORMATION
//######################################
require_once("includes/teachers.students-mgt.php");

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
        <!-- MAIN CONTENT -->
        <!-- ==================================== -->
        <div class="content">

            <!--BEGIN: NAVIGATION BAR-->
            <nav class="px-4 py-1 d-flex justify-content-center px-5 bg-white" id="nav_top">
                <div class="top_container px-3 d-flex justify-content-between align-items-center">
                    <div class="d-inline-flex align-items-center">
                        <h5 class="d-inline-flex align-items-center">
                            <span><?= $teacher_name; ?></span>
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

            <!-- BEGIN: WORKSPACE -->
            <div class="workspace px-5 mt-4 pb-2 d-flex flex-column flex-grow-1">

                <!-- BEGIN: SCHOOL NAME -->
                <div class="row-fluid">
                    <div class="col text-start">
                        <div class="school-name-wrap">
                            <h4><?= $teacher_info['school_name']; ?></h4>
                        </div>
                        <div class="total-students-wrap">
                            <p>Total Students: <?= $totalEntries ?></p>
                        </div>
                    </div>
                </div>
                <!-- END: SCHOOL NAME -->

                <!-- BEGIN: CONTROL RIBBONS -->
                <div class="row">

                    <!--BEGIN LEFT: FILTERING -->
                    <div class="col">

                        <!--BEGIN: DISPLAY ITEMS IN HORIZONTAL ROW -->
                        <div class="d-inline-flex align-items-center flex-row">

                            <!-- BEGIN: DROPDOWN -->
                            <div class="dropdown me-3">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= ($filter_mode == "mine") ? "My Students" : "All Students" ?>
                                </button>
                                <ul class="dropdown-menu filter-select" aria-labelledby="dropdownMenuButton1" style="z-index: 10;">
                                    <li><button class="dropdown-item" onclick="SetScrollPos(GetPaginatedIndex()); SetFilter('mine'); ApplyFilter();">My Students</button></li>
                                    <li><button class="dropdown-item" onclick="SetScrollPos(GetPaginatedIndex()); SetFilter('all'); ApplyFilter();">All Students</button></li>
                                </ul>
                            </div>
                            <!-- END: DROPDOWN -->

                            <!--BEGIN: SEARCHBAR-->
                            <div class="search-bar">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Find Student" aria-label="Find Student" aria-describedby="search-icon">
                                    <button class="btn btn-secondary material-icons-sharp" id="search-icon">search</button>
                                </div>
                            </div>
                            <!--END: SEARCHBAR-->
                        </div>
                        <!--END: DISPLAY ITEMS IN HORIZONTAL ROW -->

                    </div>
                    <!--END LEFT: FILTERING -->

                    <!--BEGIN RIGHT: PAGINATION -->
                    <div class="col d-flex align-items-center justify-content-end">
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <button class="btn ms-2 d-inline-flex align-items-center <?= ($currentPageIndex == $i) ? "btn-warning text-dark fw-bold" : "btn-primary text-light"; ?>" onclick="SetScrollPos(<?= $i ?>); SetFilter(GetFilter()); ApplyFilter();">
                                <?php
                                if ($i == 1) {
                                    echo "<i class='material-icons-sharp'>skip_previous</i> <span>First</span>";
                                } else if ($i == $totalPages) {
                                    echo "<i class='material-icons-sharp'>skip_next</i> <span>Last</span>";
                                } else {
                                    echo $i;
                                }
                                ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                    <!--END RIGHT: PAGINATION -->

                </div>
                <!-- END: CONTROL RIBBONS -->

                <!--BEGIN: TABLE-->
                <div class="row mt-3 d-flex flex-column flex-grow-1">
                    <div class="col p-0 position-relative">

                        <!--BEGIN: SCROLLABLE CONTENT-->
                        <div class="scrollable px-3 pt-0 position-absolute start-0 top-0 w-100 h-100" style="overflow-y: auto;">
                            <table class="table table-striped table-hover">

                                <thead class="bg-dark text-light top-0 position-sticky" style="z-index: 5;">
                                    <tr>
                                        <th class="d-none"></th>
                                        <th class="py-3 px-5">LRN</th>
                                        <th class="py-3 px-5">Name</th>
                                        <th class="py-3 px-5">Teacher-in-charge</th>
                                        <th class="py-3 px-5">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if ($totalEntries > 0) : ?>
                                        <?php foreach ($students_table as $s) : ?>
                                            <tr>
                                                <td class="d-none"><?= Utils::Obfuscate($s['LRN']); ?></td>
                                                <td class="py-3 px-5"><?= $s['LRN']; ?></td>
                                                <td class="py-3 px-5"><?= $s["StudentName"] ?></td>
                                                <td class="py-3 px-5">
                                                    <?php if (!empty($teachers_result)) : ?>
                                                        <form action="action.change-teacher-onselect.php" method="POST">
                                                            <input type="hidden" name="student-row" value="<?= Utils::Obfuscate($s['LRN']); ?>">
                                                            <select name="select-teacher" class="form-select" aria-label="Select Teacher" onchange="this.form.submit()">
                                                                <option disabled selected>Select Teacher</option>
                                                                <?php foreach ($teachers_result as $t) : ?>

                                                                    <option <?= (!empty($s['TeachersId']) && $s['TeachersId'] == $t["id"]) ? "selected" : " "; ?> value="<?= Utils::Obfuscate($t['id']) ?>"><?= $t['teacher']; ?></option>

                                                                <?php endforeach; ?>
                                                            </select>
                                                        </form>
                                                    <?php else : ?>
                                                        <?= "No teachers listed"; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="py-3 px-5">
                                                    <?php if ($s['TeacherInCharge'] != "" && $s['TeachersId'] == $authCookie["userid"]) : ?>
                                                        <div class="row">
                                                            <div class="col mt-1">
                                                                <button class="btn btn-info view-button me-2 w-100">View</button>
                                                            </div>
                                                            <div class="col mt-1">
                                                                <button type="button" class="btn btn-warning w-100" onclick="DisownStudent('<?= $s["StudentName"] ?>','<?= Utils::Obfuscate($s['LRN']); ?>')">Disown</button>
                                                            </div>
                                                        </div>
                                                    <?php elseif (empty($s['TeacherInCharge'])) : ?>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="rouded bg-danger rounded d-flex align-items-center justify-content-center p-2 text-center text-light">
                                                                    <i class="material-icons-sharp me-2">error</i>
                                                                    <span>Floating Student</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
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

                <!--BEGIN: SCROLL INDEX TRACKER-->
                <div class="row">
                    <div class="col pt-4">
                        <div class="entries-pagination-tracker">
                            <h6>
                                Showing page <span class="rounded bg-warning text-dark fw-bold px-2"><?= $currentPageIndex; ?></span> 
                                of <span class="rounded bg-primary text-light px-2"><?= $totalPages ?></span> with 
                                max. <span class="rounded bg-secondary text-light px-2"><?= $entriesPerPage ?></span> entries per table.</h6>
                        </div>
                    </div>
                </div>
                <!--END: SCROLL INDEX TRACKER-->

                <!-- END: WORKSPACE -->
            </div>
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

        <!-- script -->
        <script src="lib/jquery/jquery-3.6.1.min.js"></script>
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
            var action_disown_fired = 0;
            var seclected_paginator_index = <?= $currentPageIndex; ?>;

            $(document).ready(function() {
                $(".alertbox").modal({
                    backdrop: 'static'
                });
                // incase may error, revert to:
                //  $("td").click(function()
                $("td .view-button").click(function() {
                    var key = $(this).closest("tr").find("td:eq(0)").text();
                    $("#input_key").val(key);

                    $("#submit_key").click();
                });

                alert("Pos: " + GetPaginatedIndex() + "\n" + "filter: " + GetFilter());
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

                if (action_disown_fired == 1) {
                    $("#submit_disown").click();
                }
            }

            function MsgBoxCancelClicked() {
                action_disown_fired = 0;
                $("#target_key").val('');
            }

            function GetCellValueOnClick() {
                $("td").click(function() {
                    var rowIndex = $(this).closest("tr").index();
                    var colIndex = $(this).index();
                    alert("row index: " + rowIndex + "\nCol Index: " + colIndex);
                })
            }

            function DisownStudent(studentName, targetKey) {
                action_disown_fired = 1;
                $("#target_key").val(targetKey);
                ShowConfirm("Confirmation", `Disown <strong>${studentName}</strong>? Grades will remain intact but you wont be able to view them. Continue?`);
            }

            function GetFilter() {
                return $("#filter_students").val();
            }

            function SetFilter(mode) {
                $("#filter_students").val(mode);
            }

            function ApplyFilter() {
                $("#filter-form").submit();
            }

            function SetScrollPos(pos) {
                $("#page-index").val(pos);
            }

            function GetPaginatedIndex() {
                return seclected_paginator_index;
            }
        </script>

        <form action="teacher-view-student.php" method="POST">
            <input type="hidden" name="input_key" id="input_key">
            <input type="submit" name="submit_key" id="submit_key" class="d-none" value="">
        </form>

        <form action="action.disown-student.php" method="POST">
            <input type="hidden" name="target_key" id="target_key" value="">
            <input type="hidden" name="owner_key" id="owner_key" value="<?= Utils::Obfuscate($authCookie["userid"]); ?>">
            <input type="submit" id="submit_disown" name="submit_disown" class="d-none">
        </form>

        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" id="filter-form">
            <input type="hidden" name="page-index" id="page-index" value="<?= $currentPageIndex; ?>">
            <input type="hidden" name="filter_students" id="filter_students" value="<?= $filter_mode ?: "all"; ?>">
            <input type="hidden" name="filter_key" id="filter_key" value="<?= Utils::Obfuscate($authCookie["userid"]); ?>">
        </form>
</body>

</html>