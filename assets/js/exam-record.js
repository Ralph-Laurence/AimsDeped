$(document).ready(() =>
{
    // find the selected table row
    $(".exams-table").click(() => 
    {
        var rowIndex = $(".exams-table .exams-table-body tr").index(this);

        alert(rowIndex);
    });
});