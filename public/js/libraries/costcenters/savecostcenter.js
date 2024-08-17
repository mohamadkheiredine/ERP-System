$(function(){
	ClassicEditor
    .create( document.querySelector( '#AC_COST_CENTER_DESCRIPTION' ) )
    .then( newEditor => {
       $.ccenter_desc = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	
	$('#BTN_SAVE_COSTCENTER').on('click',costcenter_module.SaveCostCenterInfo); 
});