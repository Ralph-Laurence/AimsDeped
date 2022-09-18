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
//$sql_getclxrooms = "SELECT c.subject_name AS 'subjectName', g.level AS 'section', COUNT(c.subject_name) AS 'total' FROM `classrooms` c LEFT JOIN `teachers` t ON c.teacher_id = t.id LEFT JOIN `grade_section` g ON c.grade_section_id = g.id WHERE t.id = ? GROUP BY c.subject_name";


// SELECT ALL SECTIONS THAT BELONG TO THIS TEACHER
$handles = Constants::$TEACHER_HANDLES_TABLE;
$sql_get_sects = "SELECT 
h.subject_assign AS 'subjectName', 
g.level AS 'section',
COUNT(*) AS 'total' FROM `students` s 
LEFT JOIN $handles h on s.section_id = h.section_id
LEFT JOIN `grade_section` g ON g.id = h.section_id
WHERE h.teacher_id =?
group by h.section_id";

$sth = $db->Pdo->prepare($sql_get_sects);
$sth->execute([$authCookie["userid"]]);
$result_getclxrooms = $sth->fetchAll(PDO::FETCH_ASSOC) ?: [];
//
// Define card backgrounds
//
$card_backgrounds = [
    "classroom-card-blue text-light",
    "classroom-card-orange text-dark",
    "classroom-card-purple text-light"
];
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
    <title>My Sections</title>
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
                <li class="active d-flex align-items-center px-4">
                    <a class="text-decoration-none w-100" href="section-handles.php">
                        <i class="material-icons-outlined">meeting_room</i>
                        <span>My Sections</span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none w-100" href="exam-record.php">
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
            <nav class="px-4 py-1 d-flex justify-content-center px-5 bg-white" id="nav_top">
                <div class="top_container px-3 d-flex justify-content-between align-items-center">
                    <h4>My Sections</h4>
                    <a href="logout.php" class="px-2 text-decoration-none" id="logout_btn">
                        <i class="fa-solid fa-power-off"></i>
                        Logout
                    </a>
                </div>
            </nav>
            <!-- CLASROOM CARDS WRAPPER -->
            <div class="classroom-cards">
                <div class="row px-4">
                    <div class="row mt-4">
                        <div class="col">
                            <h6 class="fw-bold text-primary">Total Sections: <?= count($result_getclxrooms); ?></h6>
                        </div>
                    </div>
                    <?php if (!empty($result_getclxrooms)) : ?>
                        <?php foreach ($result_getclxrooms as $classroom) : ?>
                            <div class="col-6 col-lg-4 col-md-6 col-sm-6 mt-3">
                                <div class="card">
                                    <div class="card-body classroom-card-img <?php echo $card_backgrounds[array_rand($card_backgrounds)]; ?> rounded-0">
                                        <div class="row">
                                            <div class="col">
                                                <h4 class="card-title"><?= $classroom['subjectName']; ?></h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <p class="card-text">Section <?= $classroom['section']; ?></p>
                                            </div>
                                            <div class="col text-end">
                                                <p class="card-text"><?= $classroom['total']; ?> Students</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col text-start">
                                                <div class="dropdown">
                                                    <a class="btn btn-link p-0 d-inline-flex align-items-center text-dark" href="#" role="button" id="dropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-icons-sharp">more_vert</i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                                <i class="material-icons-sharp me-3">edit</i>
                                                                <span class="fw-bold">Edit</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                                <i class="material-icons-sharp me-3">exit_to_app</i>
                                                                <span class="fw-bold">Leave Section</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col text-end">
                                                <a href="#" class="btn btn-primary fw-bold text-capitalize">View</a>
                                            </div>
                                            <!--  -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
    <script src="lib/mdb/js/mdb.min.js"></script>
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