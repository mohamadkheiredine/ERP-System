/**
 * 
 */
$(function(){
	 ClassicEditor
     .create( document.querySelector( '#JS_STATUS_DESCRIPTION' ) )
     .then( newEditor => {
        $.order_editor = newEditor;
    } )
     .catch( error => {
         console.error( error );
     } );
	$('#BTN_SAVE_STATUS').on('click',jobstatus_module.SaveJobStatusInfo);
	$('select').select2();
});