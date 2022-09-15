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


//######################################
// REGION: STUDENT's INFORMATION
//######################################
require_once("includes/teachers.students-mgt.php");


// foreach($students_table as $s)
// {
//     echo "{$s['id']} -> {$s['student_lrn']} <br>";
// }
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
                    <a class="text-decoration-none w-100" href="">
                        <span class="px-4">
                            <i class="material-icons-sharp">groups</i>
                            <span>Students</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="teacher_my-classroom.php">
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
                    <span>Total Students : <?php echo $totalEntries; ?></span>
                    <a href="logout.php" class="px-2 text-decoration-none" id="logout_btn">
                        <i class="fa-solid fa-power-off"></i>
                        Logout
                    </a>
                </div>
            </nav>

            <!-- SEARCH -->
            <!-- ==================================== -->
            <div class="mt-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="left">
                        <h2>Welcome Teacher <?php echo $firstName ?>!</h2>
                        <p>Here are the list of students enrolled.</p>
                    </div>
                    <button class="py-1 px-3" id="save_btn">Save</button>
                </div>
                <!-- <div class=" mt-4 ">
                    <h6>Find Student</h6>
                </div> -->
                <div class="row">
                    <div class="col">
                        <div class="search flex d-flex align-items-center">
                            <input type="text" placeholder="Enter student name" class="px-4 py-2" id="search_field">
                            <button id="search_submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center justify-content-end">
                        <a href="<?php echo $_SERVER["PHP_SELF"] . "?page=1"; ?>" class="btn btn-primary mx-2">First</a>
                        <?php for ($i = 2; $i < $totalPages; $i++) : ?>
                            <a href="<?php echo $_SERVER["PHP_SELF"] . "?page=" . $i; ?>" class="btn btn-primary mx-2"><?= $i; ?></a>
                        <?php endfor; ?>
                        <a href="<?php echo $_SERVER["PHP_SELF"] . "?page=" . $totalPages; ?>" class="btn btn-primary mx-2">Last</a>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table_container mt-4 d-flex flex-grow-1 position-relative">
                <div class="px-5" id="table">
                    <div class="entries-pagination-tracker">
                        <h5>Showing <?= $currentPageIndex; ?> of <?= $totalPages ?> entries</h5>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="py-3 px-5">LRN</th>
                                <th class="py-3 px-5">Name</th>
                                <th class="py-3 px-5">Grade Level</th>
                                <th class="py-3 px-5"></th>
                                <th class="py-3 px-5">Teacher-in-charge</th>
                                <th class="py-3 px-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <td class="py-3 px-5">Cutie00</td>
                                <td class="py-3 px-5">Katherine Lucero Decena</td>
                                <td class="py-3 px-5">Grade 12</td>
                                <td class="py-3 px-5"></td>
                                <td class="py-3 px-5">John Doe</td>
                                <td class="py-3 px-5"><input type="checkbox"></td>
                            </tr> -->
                            <?php if ($totalEntries > 0) : ?>
                                <?php foreach ($students_table as $s) : ?>
                                    <tr>
                                        <td class="py-3 px-5"><?= $s['student_lrn']; ?></td>
                                        <td class="py-3 px-5"><?php echo $s['lastname'] . ", " . $s['firstname'] . " " . $s['middlename'] ?></td>
                                        <td class="py-3 px-5"><?= $s['grade_level']; ?></td>
                                        <td class="py-3 px-5"></td>
                                        <td class="py-3 px-5"></td>
                                        <td class="py-3 px-5"></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
    </script>

</body>

</html>