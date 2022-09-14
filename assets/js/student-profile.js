
var hasHearingImpairment = false;
var hasSpeechImpairment = false;

$(document).ready(function()
{
    /**
     * Copy residence information into permanent residence fields
     */
    $("#checkbox_sameResidence").click(function()
    {
        var checked = this.checked;

        // -> Apply readonly = true, to prevent editing
        // -> Apply focus() to prevent the value from overlapping the placeholder
        if (checked)
        {
            $("#input_permHouseNo").val($("#input_houseNo").val()).prop('readonly', true).focus();
            $("#input_permBrgy").val($("#input_brgy").val()).prop('readonly', true).focus();
            $("#input_permTownCity").val($("#input_townCity").val()).prop('readonly', true).focus();
            $("#input_permProvince").val($("#input_province").val()).prop('readonly', true).focus();
        }
        // -> Allow the input field to be edited again by setting readonly 'false'
        else
        {
            $("#input_permHouseNo").prop('readonly', false).focus();
            $("#input_permBrgy").prop('readonly', false).focus();//.trigger('blur');
            $("#input_permTownCity").prop('readonly', false).focus();
            $("#input_permProvince").prop('readonly', false).focus();
        }
    });

    /**
     * Show birthday date picker
     */
    //$("#birthday_picker").datepicker();
    /**
    * Confirmation before submitting filled out forms   
    */
    $("#checkbox_confirmAllInfo").click(function()
    {
        var checked = this.checked;
        $("#submit-button").prop('disabled', !checked);
    });

    // FOrce numeric only input
    onlyNumericInput("input_siblings");

    $(".radio-speech-impairment").click(() => 
    {
        hasSpeechImpairment = true;
        enableElementOnClick("input_speech_impair");
    });

    $(".radio-normal-speech").click(() => 
    {
        hasSpeechImpairment = false;
        disableElementOnClick("input_speech_impair", false);
    });

    $(".radio-hearing-impairment").click(() => 
    {
        hasHearingImpairment = true;
        enableElementOnClick("input_hearing_impair");
    });

    $(".radio-normal-hearing").click(() => 
    {
        hasHearingImpairment = false;
        disableElementOnClick("input_hearing_impair", false);
    });

    $(".radio-hand-used").click(() => disableElementOnClick("input_hand_used", false));

    //
    // INTERCEPT FORM SUBMISSION
    // Then apply custom form validity report
    //
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach((form) => 
    {
        form.addEventListener('submit', (event) => 
        {
            if (!form.checkValidity()) 
            {
                event.preventDefault();
                event.stopPropagation();
                ShowMsgBox("Submit Failed", "There are incomplete required fields. Please complete them.", false);
            }
            form.classList.add('was-validated');
        }, false);
    });
});