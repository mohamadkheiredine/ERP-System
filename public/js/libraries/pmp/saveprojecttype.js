/**
 *
 */

$(function(){
	ClassicEditor
    .create( document.querySelector( '#PT_TYPE_DESCRIPTION' ) )
    .then( newEditor => {
       $.ps_editor = newEditor;
   } )
    .catch( error => {
        console.error( error );
    } );
	$("#BTN_SAVE_TYPE").on('click',projecttypes_module.SaveProjectTypeInfo);
})
