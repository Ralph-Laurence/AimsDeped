<?php
require_once "includes/autoloader.inc.php";

// Load the login cookie
$authCookie = AuthSession::Load(); //Auth::LoadAuthCookie();

// If there is no cookie, force login
// Also, make sthat we are really logging in as Coord
if (empty($authCookie)) 
{
    Utils::RedirectTo("login.php");
    exit;
} 
 
// Make sure that only the coordinator can access the system
if ($authCookie[Constants::$SESSION_AUTH_USER_LEVEL] != Constants::$USER_LVL_COORDINATOR)
{
    Utils::RedirectTo("403.php");
    exit;
} 

$db = Singleton::GetDbHelperInstance();

//
// Load the user's (coordinator) information
//
$this_users_id = array("id" => $authCookie["userid"]);

$coord_query = "SELECT 
c.id,
c.username,
c.firstname,
c.middlename,
c.lastname,
c.school_assigned,
s.school_name AS 'school_name'
FROM coordinators c
LEFT JOIN schools s ON s.school_id = c.school_assigned
WHERE id =?";

$coord_sql = $db->Pdo->prepare($coord_query);
$coord_sql -> execute([$authCookie["userid"]]);
$coord_info = $coord_sql -> fetch(PDO::FETCH_ASSOC) ?: [];  //SelectRow_Where(Constants::$COORD_TABLE, $this_users_id, true);

if (empty($coord_info)) {
    // Failed to load user's data
    Utils::RedirectTo("404.php");
    exit;
}
//
// We expect that all relevant info has been loaded
// We can now continue with business logic...
$coord_name = $coord_info['firstname'] . " " . $coord_info['middlename'] . " " . $coord_info['lastname'];
$coord_id = $coord_info['id'];
$coord_school_id = $coord_info['school_assigned'];
$coord_school_name = $coord_info['school_name'];

$teachers_table = Constants::$TEACHERS_TABLE;

// SHOW ONLY TEACHERS FROM COORDINATOR's OWN SCHOOL
$query = "SELECT 
id, 
t.lastname, 
t.firstname, 
t.middlename,
t.username,
t.chmod,
s.school_id,
s.school_name AS 'school'
FROM `teachers` t
LEFT JOIN `schools` s ON s.school_id = t.school_assigned
WHERE t.school_assigned =?
ORDER BY t.lastname";

$teachers_sql = $db->Pdo->prepare($query); //("SELECT * FROM $teachers_table ORDER BY lastname");
$teachers_sql->execute([$coord_school_id]);
$teachers = $teachers_sql->fetchAll(PDO::FETCH_ASSOC);
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
    <!-- <link rel="stylesheet" href="styles/style.css"> -->
    <link rel="stylesheet" href="styles/style.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="lib/material-design-icons/material-icons.css">
    <title>Coordinator's Page</title>
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
                    <a class="text-decoration-none w-100" href="dashboarding/dashboardcoor.html">
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
                            <!-- <i class="material-icons-sharp">groups</i> -->
                            <span>Teacher Management</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="teacher_my-classroom.php">
                        <i class="material-icons-sharp">groups</i>
                        <span>Student Management</span>
                    </a>
                </li>
                <!-- <li class="d-flex align-items-center px-4">
                    <a class="text-decoration-none" href="">
                        <i class="material-icons-sharp">badge</i>
                        <span>Marks</span>
                    </a>
                </li> -->
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
                    <!-- <span>Total Teachers : <= count($teachers); ?></span> -->
                    <div class="d-inline-flex align-items-center">
                        <h5 class="d-inline-flex align-items-center">
                            <span><?= $coord_name; ?></span>
                            <i class="material-icons-sharp">arrow_right</i>
                        </h5>
                        <h5 class="text-muted fst-italic">School Coordinator</h5>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="coordinator.create-teacher.php" class="py-2 px-3 btn btn-success me-3">Create Teacher</a>
                        <button class="py-2 px-3 btn_csv" id="import_csv">Import CSV</button>
                        <input type="file" id="import_csv_file" class="d-none">

                        <a href="logout.php" class="px-2 text-decoration-none d-none d-md-block" id="logout_btn">
                            <i class="fa-solid fa-power-off" class="logout_btn_icon"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>

            <!-- SEARCH -->
            <!-- ==================================== -->
            <div class="mt-4 px-5">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div class="left">
                        <h3><?= $coord_school_name; ?></h3>
                        <!-- <p>Here are the list of teachers in the system.</p> -->
                        <p>Total Teachers : <?= count($teachers); ?></p>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="search flex d-flex align-items-center">
                                <input type="text" placeholder="Find Teacher" class="px-4 py-2" id="search_field">
                                <button id="search_submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center justify-content-end">

                        </div>
                    </div>
                </div>

            </div>

            <!-- TABS -->
            <ul class="nav nav-tabs px-5 mt-3 mt-md-1" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">All</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Selected</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Password Requests</button>
                </li>
            </ul>
            <!-- TABLE -->
            <div class="table_container d-flex flex-grow-1 position-relative">
                <div class="px-5" id="table">
                    <table class="">
                        <thead>
                            <tr>
                                <th class="d-none"></th>
                                <th class="py-3 px-5">Lastname</th>
                                <th class="py-3 px-5">Middlename</th>
                                <th class="py-3 px-5">Firstname</th>
                                <th class="py-3 px-5">Username</th>
                                <!-- <th class="py-3 px-5">Password</th> -->
                                <th class="py-3 px-5">Advisory</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($teachers)) : ?>
                                <?php foreach ($teachers as $t) : ?>
                                    <tr>
                                        <td class="d-none"><?= $t['id']; ?></td>
                                        <td class="py-3 px-5"><?= $t['lastname']; ?></td>
                                        <td class="py-3 px-5"><?= $t['middlename']; ?></td>
                                        <td class="py-3 px-5"><?= $t['firstname']; ?></td>
                                        <td class="py-3 px-5"><?= $t['username']; ?></td>
                                        <!-- <td class="py-3 px-5"><= $t['password']; ?></td> -->
                                        <td class="py-3 px-5">
                                            <form action="action.grant-teacher.php" method="POST" onchange="this.submit()">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="grant-checkbox" id="grant-checkbox" <?= ($t['chmod'] == 777) ? "checked" : " " ?> />
                                                    <label class="form-check-label" for="flexCheckChecked"><?= ($t['chmod'] == 777) ? "With Advisory" : "None" ?></label>
                                                </div>
                                                <input type="hidden" name="teacher-key" value="<?= Utils::Obfuscate($t['id']); ?>">
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- <tr>
                                <td class="py-3 px-5">Katherine Lucero Decena</td>
                                <td class="py-3 px-5">katherinedecena</td>
                                <td class="py-3 px-5">password1234</td>
                                <td class="py-3 px-5"><input type="checkbox"></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

                <div class="px-5" id="pagination_container">
                    <div class="row">
                        <div class="col">

                            <!-- <span>Showing
                                <span class="current_page_index"> 1 </span>
                                of
                                <span class="end_page_index"> 4 </span>
                                entries
                            </span> -->

                        </div>
                        <!-- <div class="col d-flex justify-content-end">
                            First
                        </div> -->
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
            $(this).hasClass('fa-bars') ? toggleOpen($(this)) : toggleClose($(this))
        })

        $("#save_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        $(".close_btn").on('click', function() {
            $(".overlay").hasClass('hide') ? $('.overlay').removeClass('hide') : $('.overlay').addClass('hide')
        })

        $("#import_csv").on('click', function() {
            $("#import_csv_file").click()
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