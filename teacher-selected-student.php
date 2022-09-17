<?php
date_default_timezone_set("Asia/Manila");
require_once "includes/autoloader.inc.php";

$lrn_key = Utils::INPUT("input_key");

$exam_query = "SELECT e.exam_subject AS 
'subject', e.exam_score AS 'score', e.exam_date AS 'date', 
CONCAT(t.firstname, ' ', t.middlename, ' ', t.lastname) AS 'administered', e.remarks AS 'remarks' 
FROM `exams` e 
left join students s on s.student_lrn = e.student_lrn 
LEFT JOIN teachers t on e.teacher_id = t.id 
WHERE s.student_lrn =?";

$db = Singleton::GetDbHelperInstance();

$sth = $db->Pdo->prepare($exam_query);
$sth->execute([Utils::Reveal($lrn_key)]);
$exam_result = $sth->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="lib/material-design-icons/material-icons.css">
    <link rel="stylesheet" href="lib/mdb/css/mdb.min.css">
    <link rel="stylesheet" href="assets/css/root.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <!--BEGIN: ROOT LAYOUT-->
    <div class="container-fluid h-100">

        <!--BEGIN: STRETCH FULL CONTAINER HEIGHT-->
        <div class="row h-100">
            <div class="col h-100">

                <!--BEGIN: FILL REMAINING HEIGHT-->

                <div class="d-flex flex-column h-100">
                    <div class="row">
                        <div class="col">

                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs mb-3" id="ex-with-icons" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active d-inline-flex align-items-center fw-bold fw-bold fs-6 text-capitalize" id="ex-with-icons-tab-1" data-mdb-toggle="tab" href="#ex-with-icons-tabs-1" role="tab" aria-controls="ex-with-icons-tabs-1" aria-selected="true">
                                        <i class="material-icons-sharp me-2">person</i>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link d-inline-flex align-items-center fw-bold fs-6 text-capitalize" id="ex-with-icons-tab-2" data-mdb-toggle="tab" href="#ex-with-icons-tabs-2" role="tab" aria-controls="ex-with-icons-tabs-2" aria-selected="false">
                                        <i class="material-icons-sharp me-2">table_view</i>
                                        <span>Exams</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->

                        </div>
                    </div>
                    <div class="row flex-grow-1">
                        <div class="col pb-3">
                            <div class="content-wrap h-100">

                                <!-- Tabs content -->
                                <div class="tab-content" id="ex-with-icons-content">
                                    <div class="tab-pane fade show active" id="ex-with-icons-tabs-1" role="tabpanel" aria-labelledby="ex-with-icons-tab-1">
                                        Profile
                                    </div>
                                    <div class="tab-pane fade" id="ex-with-icons-tabs-2" role="tabpanel" aria-labelledby="ex-with-icons-tab-2">

                                        <!--BEGIN RIBBONS-->
                                        <div class="menu-ribbons">
                                            <div class="col d-flex flex-row-reverse px-5 pb-2 mt-3">
                                                <button type="button" class="btn btn-primary btn-rounded d-inline-flex align-items-center" data-mdb-toggle="modal" data-mdb-target="#add-records-modal">
                                                    <i class="material-icons-outlined me-2">add</i>
                                                    <span>Add</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!--BEGIN RIBBONS-->

                                        <!-- BEGIN: SHEET AREA -->
                                        <div class="sheet-area p-5">
                                            <div class="row">
                                                <div class="col">
                                                    <table class="table table-sm bg-dark text-light table-striped table-hover exams-table">
                                                        <thead>
                                                            <tr>
                                                                <!-- <th class="fw-bold" scope="col">LRN</th> -->
                                                                <th class="fw-bold" scope="col">Record Name</th>
                                                                <th class="fw-bold">Score</th>
                                                                <th class="fw-bold">Proficiency</th>
                                                                <th class="fw-bold">Date</th>
                                                                <!-- <th scope="col">Retake</th> -->
                                                                <th class="fw-bold">Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="exams-table-body">
                                                            <?php if (!empty($exam_result)) : ?>
                                                                <tr>
                                                                    <th></th>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: SHEET AREA -->

                                    </div>
                                </div>
                                <!-- Tabs content -->
                            </div>
                        </div>
                    </div>
                </div>
                <!--BEGIN: FILL REMAINING HEIGHT-->

                <!-- BEGIN: ADD EXAMS WINDOW -->
                <div class="modal fade" id="add-records-modal" tabindex="-1" aria-labelledby="add-records-modal" aria-hidden="true" data-mdb-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title d-inline-flex align-items-center justify-content-start" id="modal-title">
                                    <i class="material-icons-outlined me-2">library_books</i>
                                    <span>Add New Record</span>
                                </h5>
                                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- BEGIN: WINDOW CONTENTS-->
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col px-4 mb-3">
                                        <form action="" method="POST" class="needs-validation" novalidate>
                                            <input type="hidden" name="input_key" id="input_key" value="<?= $lrn_key; ?>">
                                            <button type="submit-record" class="d-none" id="submit-record" name="submit-record"></button>

                                            <!--BEGIN: RECORD TITLE-->
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="form-outline mb-3">
                                                        <input aria-describedby="input_titleDescriptor" type="text" id="record_title" name="record_title" class="form-control" data-mdb-showcounter="true" maxlength="32" required />
                                                        <label class="form-label" for="record_title">Title</label>
                                                        <div class="form-helper"></div>
                                                        <div class="invalid-feedback ">Please provide a valid title.</div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <span id="input_titleDescriptor" class="form-text"> Max 32 characters. </span>
                                                </div>
                                            </div>
                                            <!--END: RECORD TITLE-->
                                            <hr class="hr">

                                            <!--BEGIN DATE AND SCORE-->
                                            <div class="row mb-4">
                                                <div class="col">
                                                    <div class="form-outline">
                                                        <input type="date" id="input_date_taken" name="input_date_taken" class="form-control bg-light" required value='<?php echo date('Y-m-d', strtotime($res["birthday"])) ?>' />
                                                        <label class="form-label" for="input_date_taken">Date Taken</label>
                                                        <div class="invalid-feedback">Please add a valid date.</div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-outline mb-3">
                                                        <input type="text" id="input_score" name="input_score" class="form-control" required />
                                                        <label class="form-label" for="input_score">Score</label>
                                                        <div class="form-helper"></div>
                                                        <div class="invalid-feedback ">Please provide a valid score.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--END DATE AND SCORE-->

                                            <!--BEGIN: REMARKS FIELD-->
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-outline">
                                                        <textarea class="form-control" id="input_remarks" name="input_remarks" rows="4" data-mdb-showcounter="true" maxlength="200"></textarea>
                                                        <label class="form-label" for="input_remarks">Remarks (Optional)</label>
                                                        <div class="form-helper"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--END: REMARKS FIELD-->
                                        </form>
                                    </div>
                                </div>
                                <!--WARNING-->
                                <div class="row">
                                    <div class="col px-4">
                                        <div class="alert alert-warning text-center py-2 mt-2" role="alert">
                                            <div class="d-inline-flex justify-content-center align-items-center">
                                                <i class="material-icons-outlined me-2">info</i>
                                                <span>Please double check all fields before submitting</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--WARNING-->
                            </div>
                            <!-- END: WINDOW CONTENTS-->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary btn-save-record">Save Record</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: ADD EXAMS WINDOW -->

            </div>
        </div>
        <!--BEGIN: STRETCH FULL CONTAINER HEIGHT-->

    </div>
    <!--END: ROOT LAYOUT-->
    <script src="lib/jquery/jquery-3.6.1.min.js"></script>
    <script src="lib/mdb/js/mdb.min.js"></script>

    <script>
        var mdb_modal = undefined;

        $(document).ready(() => 
        {
            // Initialize Modal Box
            mdb_modal = new mdb.Modal(document.getElementById('add-records-modal'), []);

            // Modal click on SAVE button
            $(".btn-save-record").click(() => {
                // CLicking on the save button triggers the submit button
                $("#submit-record").click();
            });

            function SaveRecord()
            {
                $.post("", {

                });
            }

            // Intercept form submissions if there are invalid fields
            (() => 
            {
                'use strict';

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation');

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms).forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();
        });
    </script>
</body>

</html>


<!-- FLEX GROW -->
<!-- https://stackoverflow.com/a/50262611 -->