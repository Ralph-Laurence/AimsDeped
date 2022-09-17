<?php

$userId = $authCookie["userid"];
$subject = htmlspecialchars(Utils::INPUT("input_examSubject"));
$gradingPeriod = htmlspecialchars(Utils::INPUT("input_gradingPeriod"));

$exam_table_set = array();

switch($gradingPeriod)
{
    // case "1m": $gradingPeriod = "1st Mid Quarter"; break;
    case "1q": $gradingPeriod = "Exam 1"; break;
    // case "2m": $gradingPeriod = "2nd Mid Quarter"; break;
    case "2q": $gradingPeriod = "Exam 2"; break;
    // case "3m": $gradingPeriod = "3rd Mid Quarter"; break;
    case "3q": $gradingPeriod = "Exam 3"; break;
    // case "4m": $gradingPeriod = "4th Mid Quarter"; break;
    case "finals": $gradingPeriod = "Finals"; break;
}
 
if (empty($userId) || empty($gradingPeriod) || empty($subject))
{
    return;
}

$exam_query = "select * from exams e
left join teacher_section_handles h on h.section_id = e.section_id
left join teachers t on h.teacher_id = t.id 
left join students s on s.student_lrn = e.student_lrn
where t.id =? and e.exam_subject = h.subject_assign and e.exam_quarter =?
ORDER BY e.student_lrn";

$sth = $db->Pdo->prepare($exam_query);
$sth->execute([$userId, $gradingPeriod]);
$exam_table_set = $sth->fetchAll(PDO::FETCH_ASSOC) ?: [];

?>