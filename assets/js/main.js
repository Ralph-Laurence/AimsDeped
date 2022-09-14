
//
// Set an Input field's value
//
function setInputVal(elemId, val) {
    $("#" + elemId).val(val);
}

//
// Set an Element's text
//
function setElemText(elemId, text) {
    $("#" + elemId).text(text);
}

function onlyDecimalInput(elemId)
{
    $("#" + elemId).on("input", function() 
    {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });
}

function onlyNumericInput(elemId)
{
    $("#" + elemId).on("input", function() 
    {
        this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');
    });
}

function disableElementOnClick(elemId, focus = true)
{
	$("#" + elemId).prop('readonly', true);

    if (focus)
        $("#" + elemId).focus();
}

function enableElementOnClick(elemId, focus = true)
{
	$("#" + elemId).prop('disabled', false).prop("readonly", false);

    if (focus)
        $("#" + elemId).focus();
}

function enableElementsOnClick(elemIds)
{
    elemIds.forEach(element => 
    {
        $("#" + element)
        .prop('disabled', false)
        .prop('readonly', false)
        .focus();
    });  
}

function disableElementsOnClick(elemIds, isReadonly = true)
{
    elemIds.forEach(element => 
    {
        $("#" + element)
        .prop('disabled', true)
        .prop('readonly', isReadonly)
        .focus();

        if (clearInput)
            $("#" + element).val('');
    });  
}

function clearInput(inputId)
{
    $("#" + inputId).val('');
}

function setInputReadOnly(elemId, val)
{
	$("#" + elemId)
    .val(val)
    .prop("disabled", false)
    .prop('readonly', true)
    .focus();
}

function unsetInputReadOnly(elemId)
{
	$("#" + elemId) 
    .prop("disabled", false)
    .prop('readonly', false)
    .focus();
}
