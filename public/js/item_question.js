/**
 * show or hide question when label is selected in
 * admin.items.edit, admin.items.update
 */

jQuery(document).ready(function($) { 

/** show/hide question if label is selected */
    function question(){ 
        if ($('#label').prop('checked')) { 
            $('#gw_fld_question').hide(500); 
            $('#question_id').val(''); 
        }else{
            $('#gw_fld_question').show(500);
        } }; 

/** run on first load */
    $('#label').ready(function(){ question(); }) 

/** run on every change */
    $('#label').change(function(){ question(); }); 

});        
