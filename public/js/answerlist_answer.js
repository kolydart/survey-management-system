/**
 * show or hide answers when type is not radio or select
 * admin.answerlists.create, admin.answerlists.edit
 */

jQuery(document).ready(function($) { 

/** show/hide answers if label is selected */

    function answers(){ 
        if (
        	$("input:radio[name=type]:checked").val() == "radio" 
        	|| $("input:radio[name=type]:checked").val() == "checkbox" 
        ) {
            $('#gw_answers').show(500);
            $("select#selectall-answers option[value='"+hidden_answer_id+"']").prop("selected",false);
        }else{
            $('#gw_answers').hide(500);
            $('select#selectall-answers option').removeAttr("selected");
            $('li.select2-selection__choice').hide(500).remove();
            $("select#selectall-answers option[value='"+hidden_answer_id+"']").prop("selected",true);
        } }; 

/** run on first load */
    $("input[type=radio][name=type]").ready(function(){ answers(); })

/** run on every change */
    $("input[type=radio][name=type]").change(function(){ answers(); });

});
