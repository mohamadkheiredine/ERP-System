/**
 * 
 */
$(function(){
	ClassicEditor
    .create( document.querySelector( '#PS_STATUS_DESCRIPTION' ) )
    .then( newEditor => {
       $.ps_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	$('select').select2();
	$("#BTN_SAVE_STATUS").on('click',planstatus_module.SavePlanStatusInfo);
})