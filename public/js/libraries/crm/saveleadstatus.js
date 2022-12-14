/**
 * 
 */
$(function(){
	ClassicEditor
    .create( document.querySelector( '#LS_STATUS_DESCRIPTION' ) )
    .then( newEditor => {
       $.ps_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	$('select').select2();
	$("#BTN_SAVE_STATUS").on('click',leadstatus_module.SaveLeadStatusInfo);
})