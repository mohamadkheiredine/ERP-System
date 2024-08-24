$(function(){
	ClassicEditor
    .create( document.querySelector( '#CC_STATUS_DESCRIPTION' ) )
    .then( newEditor => {
       $.status_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	
    $('#BTN_SAVE_STATUS').on('click',casestatus_module.SaveCaseStatusInfo); 
});